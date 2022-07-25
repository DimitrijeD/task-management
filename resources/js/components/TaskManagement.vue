<template>
    <div>
        <div>
            <button v-if="!showCreateProj" @click="openCreateProj()" class="c-pasive-btn">Create New Project</button>

            <div v-if="showCreateProj" class="border-2 border-green-400 p-2 space-y-4">
                <input type="text" v-model="newProj.name" class="c-input" placeholder="Project name">

                <ul><li v-for="error in errors" class="text-red-400">{{ error }}</li></ul>

                <div class="flex justify-between">
                    <button @click="cancelCreateProj()" class="c-cancel-btn">Cancel</button>
                    <button @click="createProj()"             
                        :class="{
                            'c-save-btn ': validNewProj,
                            'c-disabled-btn ': !validNewProj,
                        }"
                    >Save</button>
                </div>
            </div>
        </div> 

        <div v-for="project in projects">
            <project 
                :project="project"
                :key="project.id"
                v-on:deletedProject="deleteProject"
            />
        </div>
    </div>
</template>

<script>
import Project from './Project.vue'

export default {
    props: [
        'user'
    ],

    components: {
        'project': Project,
    },

    data(){
        return {
            projects: [],
            showCreateProj: false,
            newProj:{
                name: "",
            },
            errors: []
        }
    },

    computed: {
        validNewProj(){
            if(!this.newProj.name.length) return false

            return true
        }
    },

    created(){
        this.getProjects()
    },

    methods: {
        openCreateProj(){ this.showCreateProj = true },

        cancelCreateProj(){ 
            this.showCreateProj = false
            this.newProj.name = ""
        },

        getProjects(){
            axios.get('/api/projects').then( res => {
                this.projects = res.data
            })
        },

        deleteProject(project_id){ this.$delete(this.projects, this.projects.findIndex(project => project.id == project_id)) },

        createProj(){
            if(!this.validNewProj) return 
            this.errors = []
            this.validate()
            if(this.errors.length) return

            axios.post("/api/project/new", this.newProj).then(res => {
                this.$set(
                    this.projects, 
                    this.projects.length,
                    res.data
                )

                this.cancelCreateProj()
            }).catch(e => {
                //
            })
        },

        validate(){
            if(!this.newProj.name) this.errors.push("Name cannot be empty.")
        }
    },
}
</script>

<style>
.c-input{
    @apply font-light w-full p-2 bg-gray-50 border-2 border-gray-300 focus:outline-none ring-inset focus:border-blue-300 shadow-inner;
}

.c-cancel-btn{
    @apply font-light h-12 border-2 border-gray-200 bg-gray-50 hover:border-gray-300 py-2 px-4 select-none shadow-inner cursor-pointer;
}


.c-pasive-btn{
    @apply font-light h-12 px-6 py-2 border-2 border-gray-300 hover:bg-green-50 hover:border-green-400 select-none shadow-inner cursor-pointer;
}

.c-danger-btn{
    @apply font-light h-12 border-2 border-gray-300 bg-gray-50 hover:bg-red-50 text-red-400 hover:text-red-500  py-2 px-4 cursor-pointer select-none shadow-inner;
}

.c-save-btn{
    @apply font-light h-12 border-2 border-green-300 bg-green-400 hover:border-green-500 text-white py-2 px-4 select-none shadow-inner cursor-pointer;
}

.c-disabled-btn{
    @apply font-light h-12 bg-gray-100 border-2 border-gray-200 hover:border-red-200 py-2 px-4 cursor-not-allowed shadow-inner select-none;
}
</style>