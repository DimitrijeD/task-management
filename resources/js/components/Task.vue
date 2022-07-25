<template>
    <div class="p-3 border-2 cursor-pointer shadow shadow-inner"
        :class="[configurableCls.layout]"
        draggable
        @dragstart="startDrag($event)"
        @dragend="endDrag($event)"
        v-on:dblclick="showEdit()"
    >
        <div v-if="editTask">
            <input class="c-input" v-model="newD.name" placeholder="Task name">
            <input class="my-3 c-input" v-model="newD.description" placeholder="Task description">
            
            <div class="flex justify-between">
                <div>
                    <button v-if="!awaitsDeleteConfirmation" @click="awaitDeleteConfirmation()" class="c-danger-btn">Delete</button>
                    <button v-if="awaitsDeleteConfirmation" @click="deleteTask()" class="c-danger-btn ">Are you sure?</button>
                </div>

                <button @click="closeEdit" class="c-cancel-btn">Close Edit</button>

                <div>
                    <span v-if="!anyChanges" class="mr-2 text-gray-600">First make changes then save</span>
                    <button 
                        class="px-4 py-2"
                        :class="{
                            'c-disabled-btn': !anyChanges,
                            'c-save-btn': anyChanges,
                        }"
                        @click="saveChanges()"
                    >Save changes</button>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="font-medium break-all">{{ task.name }}</div>
            <div class="my-2 py-2 overflow-y-auto max-h-96 break-all"
                :class="[configurableCls.descriptionBorders]"
            >{{ task.description }}</div>
            <span class="text-sm text-right font-light">{{ dateHumans }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'task', 'configurableCls'
    ],

    components: {
        
    },

    data(){
        return {
            editTask: false,
            awaitsDeleteConfirmation: false,
            newD: {
                name: this.task.name,
                description: this.task.description
            }
        }
    },

    computed: {
        dateHumans(){ return new Date(this.task.created_at).toISOString().substring(0, 10) },

        anyChanges(){
            if(this.newD.name        != this.task.name)        return true
            if(this.newD.description != this.task.description) return true
            return false
        }
    },

    methods: {
        awaitDeleteConfirmation(){ this.awaitsDeleteConfirmation = true },

        showEdit(){ this.editTask = true },

        closeEdit(){ this.editTask = false },

        deleteTask(){
            axios.delete(`/api/task/delete/${this.task.id}`).then(res=> {
                // res.data - status msg
                this.$emit('deletedTask', this.task.id)
                 this.awaitsDeleteConfirmation = false
            }).catch(e => {
                //
            })
        },

        startDrag(e) {
            e.dataTransfer.dropEffect = 'move'
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('task_id', this.task.id)
        },

        endDrag(e){

        },

        saveChanges(){
            if(!this.anyChanges) return 

            const task = {
                name: this.newD.name,
                description: this.newD.description,
                priority: this.task.priority,
                project_id: this.task.project_id,
                task_id: this.task.id
            }

            axios.patch("/api/task/update", task).then(res=> {
                this.task.name = res.data.name
                this.task.description = res.data.description 
                this.task.priority = res.data.priority 
                this.task.updated_at = res.data.updated_at 

            }).catch(e => {
                //
            })
        }
    },
}
</script>