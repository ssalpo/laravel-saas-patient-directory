<template>
    <BsModal
        ref="modal"
        title="Новое учреждение"
        with-btn
        centered
        @hidden="form.reset()"
        @submit="submit"
    >
        <template #btn="{show}">
            <div class="mt-2">
                <button type="button" @click="show" class="btn btn-sm btn-link">
                    + Новое учреждение
                </button>
            </div>
        </template>


        <form-input
            label="Название"
            required
            v-model.trim="form.name"
            :validation-error="form.errors.get('name')"
        />

        <template #footer="{hide}">
            <button class="btn btn-primary" :disabled="form.busy">Добавить</button>

            <button type="button" @click="hide" class="btn btn-link link-secondary ms-auto">Отменить</button>
        </template>
    </BsModal>
</template>

<script>
import Form from 'vform'
import BsModal from "../BsModal.vue";
import FormInput from "../Form/FormInput.vue";
import FormSelectPermissions from "../Form/FormSelectPermissions.vue";

export default {
    emits: ['success'],
    name: "NewMedicalClinicModal",
    components: {FormSelectPermissions, FormInput, BsModal},
    data() {
        return {
            form: new Form({
                name: null
            }),
        }
    },
    methods: {
        async submit() {
            try {
                const {data} = await this.form.post(route('medical-clinics.store', {modal: 1}));

                this.$emit('success', data);

                this.form.reset();

                this.$refs.modal.hide();
            } catch (e) {
                alert('Ошибка добавления нового учреждения.');
            }
        }
    }
}
</script>
