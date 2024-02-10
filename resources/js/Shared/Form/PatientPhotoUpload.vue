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
        upload(photos) {
            this.form.photos = photos;

            this.form.post(route('patients.photos.store', {patient: this.patientId}), {
                forceFormData: true,
                onSuccess: () => {
                    this.$refs.photos.files = null;
                    this.form.reset('photos')
                },
                preserveState: true,
                preserveScroll: true,
            })
        }
    }
})
</script>

<template>
    <div v-if="form.progress">
        <progress :value="form.progress.percentage" max="100">
            {{ form.progress.percentage }}%
        </progress>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="custom-file">
                <input @input="upload($event.target.files)"
                       ref="photos"
                       multiple
                       :disabled="form.processing"
                       :class="{'is-invalid': $page.props.errors.photos}"
                       type="file" class="custom-file-input"/>

                <label class="custom-file-label">
                    {{ form.photos.length ? `Выбрано ${form.photos.length}` : 'Выберите фотографии' }}
                </label>

                <div v-if="$page.props.errors.photos" class="error invalid-feedback">
                    {{ $page.props.errors.photos }}
                </div>
            </div>
        </div>
    </div>
</template>
