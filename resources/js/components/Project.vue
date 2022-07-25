<template>
    <div class="my-5 p-2 border-2 border-blue-200 shadow-md shadow-inner" @mouseleave="showMore = false">
        <!-- Header -->
        <div class="text-lg mb-2 border-b-2 border-blue-50">
            <div v-if="!editProj" class="cursor-pointer flex">
                <!-- Left Side Static Head -->
                <div class="flex-grow font-medium text-lg" v-on:dblclick="showEditProject(1)" @mouseover="showMore = true">
                    {{ project.name }} 
                </div>

                <!-- Rigth Side Static Head -->
                <div class="space-y-1 mt-1 mr-1">
                    <svg @click="showProj = !showProj" class="w-4" viewBox="0 0 14 14"><path d="M.5,3.85,6.65,10a.48.48,0,0,0,.7,0L13.5,3.85" fill="none" stroke="#575656" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </div>
            </div>
            <div v-else class="mb-4">
                <edit-project 
                    :project="project"
                    v-on:showEditProject="showEditProject" 
                    v-on:updateProject="updateProject" 
                    v-on:deletedProject="deleteProject" 
                />
            </div>
        </div>
        <!-- End Header -->

        <!-- Minimizeable Body -->
        <div :class="{'hidden': !showProj}">
            <div>
                <div v-if="isCreatingTask">
                    <create-task 
                        :project_id="project.id"
                        :priority="getMaxPrio + 1"
                        v-on:createdTask="createdTask" 
                        v-on:cancel="cancelNewTask" 
                    />
                </div>
                <div v-else >
                    <!-- Additional Options -->
                    <div v-if="showMore" class="flex justify-end">
                        <div class="flex items-center mx-4 rounded-sm px-2 cursor-pointer select-none" @click="{usesUniqueColorsPerTask = !usesUniqueColorsPerTask}">
                            <label for="checkbox" class="text-gray-600 pr-1">Toggle Tasks Colors</label>
                            <input type="checkbox" v-model="usesUniqueColorsPerTask">
                        </div>

                        <button @click="openCreatingTask()" class="c-pasive-btn">Add Task</button>
                    </div>
                    <!-- End Additional Options -->
                </div>
            </div>

            <!-- Tasks and Slots List -->
            <div>
                <!-- Top Task Dropdown Slot -->
                <div 
                    :class="[slotCls.static]"
                    :ref="getTopRef"
                    @drop.prevent="onDrop($event, null, null, edges.top)"
                    @dragover.prevent
                    @dragenter.prevent=dragEnter(getTopRef) 
                    @dragleave.prevent="dragLeave(getTopRef)"
                ></div>

                <div v-for="(task, index) in tasks">
                    <task 
                        :task="task"
                        :key="task.id"
                        :configurableCls="getUniqueBgColor(task.id)"
                        v-on:deletedTask="deleteTask"
                    />

                    <!-- In-between Tasks Dropdown Slot -->
                    <div 
                        v-if="index != Object.keys(tasks).length - 1"
                        :class="[slotCls.static]"
                        :ref="getMiddleSlotRef(index)"
                        @drop.prevent="onDrop($event, index, index + 1, null)"
                        @dragover.prevent
                        @dragenter.prevent=dragEnter(getMiddleSlotRef(index)) 
                        @dragleave.prevent="dragLeave(getMiddleSlotRef(index))"
                    ></div>
                </div>

                <!-- In-between Tasks Dropdown Slots -->
                <div 
                    :class="[slotCls.static]"
                    :ref="getBotRef"
                    @drop.prevent="onDrop($event, null, null, edges.bot)"
                    @dragover.prevent
                    @dragenter.prevent=dragEnter(getBotRef) 
                    @dragleave.prevent="dragLeave(getBotRef)"
                ></div>
            </div>
            <!-- End Tasks and Slots List -->
        </div>
        <!-- End Minimizeable Body -->
    </div>
</template>

<script>
import Task from './Task.vue'
import CreateTask from './CreateTask.vue'
import EditProject from './EditProject.vue';

export default {
    props: [
        'project'
    ],

    components: {
        'task': Task,
        'create-task': CreateTask,
        'edit-project': EditProject
    },

    computed: {
        tasks() {
            return this.project.tasks.sort(function (a, b) {
                return a.priority - b.priority;
            }).reverse();
        },

        getBotRef(){ return this.refs.bot + this.project.id },

        getTopRef(){ return this.refs.top + this.project.id },

        getMaxPrio(){ return Math.max.apply(Math, this.tasks.map(function(task) { return task.priority; })) },

        getMinPrio(){ return Math.min.apply(Math, this.tasks.map(function(task) { return task.priority; })) },

        numColorValues(){ return this.bgColorCls.values.length },
    },

    data(){
        return {
            showProj: true,
            showMore: false,
            editProj: false,
            isCreatingTask: false,
            usesUniqueColorsPerTask: false,
            newName: this.project.name,
            bgColorCls: {
                colors: [ "indigo", "violet", "purple", "rose", "orange", "lime", "emerald", "teal", "cyan", "sky", "blueGray", "amber"],
                values: ["100", "200", "300"] // do not add "50", it's exception
            },

            edges: {
                top: "top",
                bot: "bottom"
            },
            dontUpdatePrio: "break",   
            slotCls: {
                static: ["py-3"], // Slot classes when task is not being dragged and hovered over 
                active: ["py-16", "bg-sky-200", "my-2"] // Slot classes when task is being dragged and hovered over
            },
            refs:{
                top: "top_slot_",
                middle: "middle_slot_",
                bot: "bot_slot_",
            },
            staticPriorityExpander: 1,
            debounce: null         
        }
    },

    created(){

    },

    mounted() {
        
    },

    methods: {
        deleteTask(task_id){ this.tasks.splice(this.tasks.findIndex(task => task.id == task_id), 1) },

        createdTask(task){ this.project.tasks.push(task) },

        cancelNewTask(){ this.isCreatingTask = false },

        openCreatingTask(){ this.isCreatingTask = true },

        showEditProject(v){ this.editProj = v },

        updateProject(proj){
            this.project.name = proj.name
            this.project.updated_at = proj.updated_at

            this.showEditProject(0)
        },

        deleteProject(id){
            this.$emit('deletedProject', id)
        },

        getMiddleSlotRef(index){ return this.refs.middle + index },

        dragEnter(ref){
            this.debounce = setTimeout(() => {
                if(Array.isArray(this.$refs[ref])){
                    this.$refs[ref][0].classList.remove(...this.slotCls.static)
                    this.$refs[ref][0].classList.add(...this.slotCls.active)
                } else {
                    this.$refs[ref].classList.remove(...this.slotCls.static)
                    this.$refs[ref].classList.add(...this.slotCls.active)
                }
            }, 50)
        } ,

        dragLeave(ref){
            clearTimeout(this.debounce)         

            setTimeout(() => {
                if(Array.isArray(this.$refs[ref])){
                    this.$refs[ref][0]?.classList.remove(...this.slotCls.active)
                    this.$refs[ref][0]?.classList.add(...this.slotCls.static)
                } else {
                    this.$refs[ref]?.classList.remove(...this.slotCls.active)
                    this.$refs[ref]?.classList.add(...this.slotCls.static)
                }
            }, 200)
        },

        resetAllSlotStyle(){
            this.dragLeave(this.getTopRef)
            this.dragLeave(this.getBotRef)
            for(let i in this.tasks){
                this.dragLeave(this.getMiddleSlotRef(i))
            }
        },

        onDrop(e, topIndex = null, bottomIndex = null, edge = null) {
            this.resetAllSlotStyle()

            this.updateTaskPriority(
                e.dataTransfer.getData('task_id'), 
                topIndex,
                bottomIndex,
                edge
            )
        },

        updateTaskPriority(task_id, topIndex, bottomIndex, edge){
            const priority = this.calcPriority(task_id, topIndex, bottomIndex, edge)

            if(priority === this.dontUpdatePrio) return
            
            const data = {
                task_id: task_id, 
                project_id: this.project.id,
                priority: priority
            }

            axios.patch('/api/task/update', data).then(res => {
                // remove old task
                this.tasks.splice(this.tasks.findIndex(task => task.id == task_id), 1)

                // add refreshed task
                this.tasks.push(res.data )
            }).catch(e => {
                //
            })
        },

        calcPriority(task_id, topIndex, bottomIndex, edge){
            // Is user's intent to put this task on top of list
            if(edge == this.edges.top) {
                if(this.isTaskAtTop(task_id)) return this.dontUpdatePrio// prevent useless APIs

                return this.getMaxPrio + this.staticPriorityExpander
            }
            
            // Is user's intent to put this task on bottom of list
            if(edge == this.edges.bot) {
                if(this.isTaskAtBot(task_id)) return this.dontUpdatePrio // prevent useless API

                return this.getMinPrio - this.staticPriorityExpander
            }

            // Task is placed in middle of stack
            return this.calculateMiddlePriority(this.tasks[topIndex],this.tasks[bottomIndex])
        },

        calculateMiddlePriority(topTask, bottomTask){
            const topPrio = parseFloat(topTask.priority)
            const botPrio = parseFloat(bottomTask.priority)
            return (topPrio + botPrio) / 2 
        },

        /**
         * Treats this.bgColorCls.colors as circular array by asinging different backgroud colors to each task 
         */
        getUniqueBgColor(task_id){
            if(!this.usesUniqueColorsPerTask) return ""

            const color = this.bgColorCls.colors[task_id % this.bgColorCls.colors.length]

            let values = this.getIncrementedValuesOnly( this.bgColorCls.values[task_id % this.numColorValues], 3)

            if(!values){
                values = {
                    1: this.bgColorCls.values[task_id       % this.numColorValues],
                    2: this.bgColorCls.values[(task_id + 1) % this.numColorValues],
                    3: this.bgColorCls.values[(task_id + 2) % this.numColorValues]
                } 
            }

            return {
                layout: `bg-${color}-${values[1]} border-${color}-${values[2]}`,
                descriptionBorders: `border-b-2 border-t-2 border-${color}-${values[2]} hover:border-${color}-${values[3]}`  
            } 
        },

        getIncrementedValuesOnly(fromValue, needs){
            if(needs > this.numColorValues) return 
            let values = {}
            fromValue = parseInt(fromValue)

            for(let i = 1; i <= needs; i++){
                values[i] = fromValue
                fromValue += 100 
            }

            return values
        },

        isTaskAtTop(task_id){ return this.tasks[this.tasks.findIndex(task => task.id == task_id)].priority == this.getMaxPrio },

        isTaskAtBot(task_id){ return this.tasks[this.tasks.findIndex(task => task.id == task_id)].priority == this.getMinPrio },

    }
}
</script>

<style>
.dropbtn {
    border: none;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    z-index: 1;
}

.dropdown-content a {
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>