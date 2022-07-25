<template>
    <div class="m-4 space-y-2">
        <div class="flex my-2 ">
            <div class="flex-grow space-y-2 mt-2">
                <input type="text" v-model="name" class="c-input" placeholder="Task name">
                <input type="text" v-model="description" class="c-input" placeholder="Task description">
            </div>
        </div>

        <div class="flex justify-between">
            <button @click="cancel" class="c-cancel-btn">Cancel</button>
            <button 
                @click="createNewTask" 
                class=""
                :class="{
                    'c-save-btn': validTask,
                    'c-disabled-btn': !validTask,
                }"
            >Create New Task</button>
        </div>
        <ul v-if="errors.length">
            <li v-for="error in errors" class="text-red-400 text-sm">
                {{ error }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    props: [
        'project_id', 'priority'
    ],

    data(){
        return {
            name: "",
            description: "",
            errors: []
        }
    },

    computed: {
        validTask(){
            if(!this.name) return false
            // if(!this.description) return false
            
            return true
        }
    },

    methods: {
        createNewTask(){
            this.errors = []

            this.validate()

            if(this.errors.length) return 

            const data = {
                name: this.name,
                description: this.description,
                project_id: this.project_id,
                priority: this.priority
            }

            axios.post("/api/task/new", data).then(res => {
                this.$emit('createdTask', res.data)
                this.cancel()
            }).then(e => {

            })
        },

        validate(){
            if(!this.name){
                this.errors.push('Name is required')
                return
            }

            // if(!this.description){
            //     this.errors.push('Description is required')
            //     return
            // }
        }, 

        cancel(){ 
            this.$emit('cancel')
            this.name = ""
            this.description = ""
        }
    }
}
</script>