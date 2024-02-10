<template>
    <BsModal
        ref="modal"
        title="Редактирование"
        centered
        @hidden="form.reset()"
        @submit="submit"
    >
        <FormInput
            v-model="form.label"
            :validation-error="form.errors.get('label')"
        />

        <template #footer="{hide}">
            <button class="btn btn-primary" :disabled="form.busy">
                Сохранить
            </button>

            <button type="button" @click="hide" class="btn btn-link link-secondary ms-auto">
                Отменить
            </button>
        </template>
    </BsModal>
</template>

<script>
import Form from 'vform'
import BsModal from "../BsModal.vue";
import FormInput from "../Form/FormInput.vue";

export default {
    props: {
        isOpen: Boolean
    },
    emits: ['success'],
    name: "PatientPhotoLabelEditModal",
    components: {FormInput, BsModal},
    data() {
        return {
            patientId: null,
            photoId: null,
            form: new Form({
                label: this.label,
            }),
        }
    },
    methods: {
        submit() {
            try {
                this.form
                    .post(route('patients.photos.update-label', {
                        patient: this.patientId,
                        photo: this.photoId,
                        modal: 1
                    }))
                    .then(() => {
                        this.$emit('success');

                        this.form.reset();

                        this.$refs.modal.hide();
                    });
            } catch (e) {
                console.log(e);

                alert('Ошибка сохранения.');
            }
        },
        edit(label, patientId, photoId) {
            this.form.label = label
            this.patientId = patientId
            this.photoId = photoId

            this.$refs.modal.toggle();
        }
    },
    watch: {
        isOpen(status) {
            if (status) this.$refs.modal.show()
            else this.$refs.modal.hide()
        }
    }
}
</script>
