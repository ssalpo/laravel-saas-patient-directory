<script>
import {defineComponent} from 'vue'
import FormTextarea from "./FormTextarea.vue";
import FormSaveButton from "./FormSaveButton.vue";
import {useForm} from "@inertiajs/vue3";

export default defineComponent({
    props: ['patientId', 'errors'],
    name: "SendPatientConsultation",
    components: {FormSaveButton, FormTextarea},
    data() {
        return {
            form: useForm({
                patient_id: this.patientId,
                content: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('patient-consultations.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => this.form.reset('content'),
            })
        }
    }
})
</script>

<template>
    <form @submit.prevent="submit">
        <FormTextarea
            v-model="form.content"
            placeholder="Введите текст комментария"
            :validation-error="errors.content"
        />

        <form-save-button class="btn btn-sm btn-outline-primary" />
    </form>
</template>
