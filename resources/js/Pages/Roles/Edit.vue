<template>
    <Head>
        <title>{{role?.id ? 'Обновление данных роли' : 'Новая роль'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ role?.id ? 'Обновление данных роли' : 'Новая роль' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <form-input
                            label="Ключ"
                            required
                            placeholder="admin"
                            v-model.trim="form.name"
                            :validation-error="errors.name"
                        />

                        <form-input
                            label="Название"
                            placeholder="Администратор"
                            required
                            v-model.trim="form.readable_name"
                            :validation-error="errors.readable_name"
                        />

                        <form-select-permissions
                            v-if="form.name !== 'admin'"
                            v-model="form.permissions"
                            :validation-error="errors['permissions'] || errors?.permissions"
                        />
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <form-save-button
                            :is-processing="form.processing"
                            :is-editing="role?.id"
                        />

                        <form-cancel-button
                            :url="route('roles.index')"
                            :is-processing="form.processing"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/vue3";
import FormSaveButton from "../../Shared/Form/FormSaveButton.vue";
import FormCancelButton from "../../Shared/Form/FormCancelButton.vue";
import FormInput from "../../Shared/Form/FormInput.vue";
import FormSelectPermissions from "../../Shared/Form/FormSelectPermissions.vue";

export default {
    props: ['role', 'errors'],
    components: {FormSelectPermissions, FormInput, FormCancelButton, FormSaveButton, Head, Link},
    data() {
        return {
            form: useForm({
                name: this.role?.name,
                readable_name: this.role?.readable_name,
                permissions: this.role?.permissions.map(e => e.id) || []
            }),
        }
    },
    methods: {
        submit() {
            if (!this.role?.id) {
                this.form.post('/roles');
                return;
            }

            this.form.put(`/roles/${this.role.id}`)
        }
    }
}
</script>
