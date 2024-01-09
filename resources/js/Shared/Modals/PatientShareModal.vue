<template>
    <BsModal
        ref="modal"
        title="Получить консультацию"
        with-btn
        centered
        @hidden="form.reset()"
        @submit="submit"
    >
        <template #btn="{show}">
            <button type="button" @click="show" :class="btnClass || 'btn btn-danger'">
                <i class="fa fa-share-alt"></i>
            </button>
        </template>

        <form-select-users
            v-model="form.user_id"
            prefetch
            label="Выберите пользователя"
            :validation-error="form.user_id"
        />

        <template #footer="{hide}">
            <button class="btn btn-primary" :disabled="form.busy">
                Добавить
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
import FormSelectUsers from "../Form/FormSelectUsers.vue";

export default {
    props: {
        patient: Object,
        btnClass: {
            type: String,
            default: ''
        }
    },
    emits: ['success'],
    name: "SharePatientModal",
    components: {FormSelectUsers, FormInput, BsModal},
    data() {
        return {
            form: new Form({
                user_id: this.patient.share_to_user_id,
                patient_id: this.patient.id,
            }),
        }
    },
    methods: {
        submit() {
            try {
                this.form.post(route('patient-shares.store', {modal: 1}));

                this.$emit('success');

                this.form.reset();

                this.$refs.modal.hide();
            } catch (e) {
                console.log(e);

                alert('Ошибка сохранения.');
            }
        }
    }
}
</script>
