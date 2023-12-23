<template>
    <BsModal
        ref="modal"
        title="Новый врач"
        with-btn
        centered
        @hidden="form.reset()"
        @submit="submit"
    >
        <template #btn="{show}">
            <div class="mt-2">
                <button type="button" @click="show" class="btn btn-sm btn-link">
                    + Новый врач
                </button>
            </div>
        </template>


        <form-input
            label="Имя"
            required
            placeholder="Султонов Рустам Алпомишевич"
            v-model.trim="form.name"
            :validation-error="form.errors.get('name')"
        />

        <form-input
            label="Номер телефона"
            v-model.trim="form.phone"
            v-maska data-maska="+############"
            placeholder="пример: +992929927233"
            :validation-error="form.errors.get('phone')"
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
import FormSelectPermissions from "../Form/FormSelectPermissions.vue";

export default {
    emits: ['success'],
    name: "NewDoctorModal",
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
                const {data} = await this.form.post(route('doctors.store', {modal: 1}));

                this.$emit('success', data);

                this.form.reset();

                this.$refs.modal.hide();
            } catch (e) {
                alert('Ошибка добавления нового доктора.');
            }
        }
    }
}
</script>
