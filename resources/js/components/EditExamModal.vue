<template>
    <modal name="my-first-modal">
        <form action="" class="row">
            <div class="col-md-12 mb-4">
                <label for="">Start Time</label>
                <input type="datetime-local" :class="{'is-invalid' : form.errors.has('start_time')}" 
                    class="form-control" placeholder="Exam name" v-model="form.start_time">
                <HasError :form="form" field="start_time" />
            </div>

            <div class="col-md-12 mb-4">
                <label for="">End Time</label>
                <input type="datetime-local" :class="{'is-invalid' : form.errors.has('end_time')}" 
                    class="form-control" placeholder="Exam name" v-model="form.end_time">
                <HasError :form="form" field="end_time" />
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                examId: this.$route.params.examId,
                name: "",
                start_time: "",
                end_time: "",
                schoolId: "",
                classId: "",
                sectionId: "",
                category: "",
                formla: "",
                status: "draft",
            }),
        }
    }
}
</script>

<style>

</style>