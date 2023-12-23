<template>
    <BsModal
        ref="modal"
        title="Новая роль"
        with-btn
        centered
        @hidden="form.reset()"
        @submit="submit"
    >
        <template #btn="{show}">
            <div class="mt-2">
                <button type="button" @click="show" class="btn btn-sm btn-link">
                    + Новая роль
                </button>
            </div>
        </template>


        <form-input
            label="Ключ"
            required
            placeholder="admin"
            v-model.trim="form.name"
            :validation-error="form.errors.get('name')"
        />

        <form-input
            label="Название"
            placeholder="Администратор"
            required
            v-model.trim="form.readable_name"
            :validation-error="form.errors.get('readable_name')"
        />

        <form-select-permissions
            v-model="form.permissions"
            :validation-error="form.errors.get('permissions.0') || form.errors.get('permissions')"
            v-if="form.name !== 'admin'"
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
    name: "NewRoleModal",
    components: {FormSelectPermissions, FormInput, BsModal},
    data() {
        return {
            form: new Form({
                name: null,
                readable_name: null,
                permissions: []
            }),
        }
    },
    methods: {
        async submit() {
            try {
                const {data} = await this.form.post(route('roles.store', {modal: 1}));

                this.$emit('success', data);

                this.form.reset();

                this.$refs.modal.hide();
            } catch (e) {
                alert('Ошибка добавления нового клиента.');
            }
        }
    }
}
</script>
