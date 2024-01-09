<script>
import {defineComponent} from 'vue'
import FormTextarea from "./Form/FormTextarea.vue";
import {useForm} from "@inertiajs/vue3";
import FormSaveButton from "./Form/FormSaveButton.vue";

export default defineComponent({
    name: "PatientEditable",
    components: {FormSaveButton, FormTextarea},
    props: {
        errors: Object,
        canEdit: {
            type: Boolean,
            default: true
        },
        patientId: {
            type: Number,
            required: true
        },
        value: {
            type: String,
            required: true
        },
        field: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            isEditing: false,
            form: useForm({
                [this.field]: this.value
            })
        }
    },
    methods: {
        submit() {
            this.form.post(
                route('patients.save.report', {patient: this.patientId}),
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => this.isEditing = false
                }
            )
        }
    }
})
</script>

<template>
    <form v-if="isEditing && canEdit" @submit.prevent="submit">
        <FormTextarea
            v-model="form[field]"
            :validation-error="$page.props.errors[field] || null"
        />

        <form-save-button
            class="btn btn-sm btn-outline-primary"
            is-editing
            :is-processing="form.processing"
        />
    </form>

    <div v-if="!isEditing">
        <slot name="text" :value="form[field]">
            <p>{{value}}</p>
        </slot>

        <a
            v-if="canEdit"
            href="#"
            @click.prevent="isEditing = true"
        >
            редактировать
        </a>
    </div>

</template>
