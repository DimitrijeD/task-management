<template>
    <div>
        <div class="flex flex-row">
            <div class="flex-grow mr-1">
                <input type="text" v-model="newName" class="c-input" placeholder="Project name">
            </div>

            <div>
                <button @click="updateProject"
                    :class="{
                        'c-save-btn': validNewName,
                        'c-disabled-btn': !validNewName
                    }" 
                >Submit</button>
                <button class="c-cancel-btn" @click="showEditProject(0)">Close</button>
            </div>
        </div>

        <div class="mt-4 ">
            <div v-if="!awaitsDeleteConfirmation">
                <button  @click="awaitsDelete(1)" class="c-danger-btn">Delete this project</button>
            </div>
            <div v-else class="space-x-2 ">
                <span class="text-sm select-none">Are you sure?</span>
                <button @click="deleteProject()" class="c-danger-btn">Yes</button>
                <button @click="awaitsDelete(0)" class="c-cancel-btn">No </button>
            </div>
        </div>
    </div>
</template>

<script>

export default{
    props: [
        "project", 
    ],

    data(){
        return {
            newName: this.project.name,
            awaitsDeleteConfirmation: false
        }
    },

    computed: {
        validNewName(){ return this.newName.length && this.project.name != this.newName }

    },

    methods: {
        deleteProject(){
            axios.delete(`/api/project/delete/${this.project.id}`).then(res=> {
                // res.data - status msg
                this.$emit('deletedProject', this.project.id)
                this.awaitsDelete(0)
            }).catch(e => {
                //
            })
        },

        awaitsDelete(b){
            this.awaitsDeleteConfirmation = b
        },

        showEditProject(b){
            this.$emit('showEditProject', b)
        },

        updateProject(){
            if(!this.validNewName) return

            axios.patch("/api/project/update", {
                name: this.newName,
                project_id: this.project.id
            }).then(res => {
                this.$emit('updateProject', res.data)
            })
        },
    }
}
</script>