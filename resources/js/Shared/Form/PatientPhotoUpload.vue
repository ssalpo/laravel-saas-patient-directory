<script>
import {defineComponent} from 'vue'
import {useForm} from "@inertiajs/vue3";
import FormInput from "./FormInput.vue";
import FormSaveButton from "./FormSaveButton.vue";

export default defineComponent({
    name: "PatientPhotoUpload",
    props: ['patientId', 'errors'],
    components: {FormSaveButton, FormInput},
    data() {
        return {
            form: useForm({
                photos: []
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('patients.photos.store', {patient: this.patientId}), {
                forceFormData: true,
                onSuccess: () => this.form.reset('photos'),
                preserveState: true,
                preserveScroll: true,
            })
        },
        remove(index) {
            this.form.photos.splice(index, 1)
        },
        add() {
            this.form.photos.push({
                file: null,
                label: null
            })
        }
    }
})
</script>

<template>
    <form @submit.prevent="submit">

        <div class="row" v-for="(photo, index) in form.photos">
            <div class="col-md">
                <div class="form-group">
                    <div class="custom-file">
                        <input @input="photo.file = $event.target.files[0]"
                               :class="{'is-invalid': errors[`photos.${index}.file`]}"
                               type="file" class="custom-file-input">
                        <label class="custom-file-label">
                            {{ photo.file?.name || 'Выберите фото' }}
                        </label>

                        <div v-if="errors[`photos.${index}.file`]" class="error invalid-feedback">
                            {{ errors[`photos.${index}.file`] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <FormInput v-model="photo.label"
                           :validation-error="errors[`photos.${index}.label`]"
                           placeholder="Введите название при необходимости"/>
            </div>
            <div class="col-md">
                <button @click="remove(index)" type="button" class="btn btn-outline-danger">
                    <span class="fa fa-trash mr-2 mr-md-0"></span>
                    <span class="d-sm-inline-block d-md-none">Удалить фото</span>
                </button>
            </div>

            <div class="col-md d-sm-block d-md-none">
                <hr/>
            </div>
        </div>

        <div v-if="form.progress">
            <progress :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
        </div>

        <button type="button" @click="add" :disabled="form.processing" class="btn btn-sm btn-outline-primary pr-3 pl-3 mr-2">+</button>

        <form-save-button
            class="btn btn-sm btn-outline-danger"
            v-if="form.photos.length"
            :is-processing="form.processing"
            add-btn-label="Сохранить"
        />
    </form>
</template>
