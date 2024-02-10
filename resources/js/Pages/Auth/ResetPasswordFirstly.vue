<template>
    <Head>
        <title>Сброс пароля</title>
    </Head>

    <div class="login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <span class="h2">Новый пароль</span>
                </div>
                <div class="card-body">

                    <form @submit.prevent="submit">
                        <div class="input mb-3">
                            <input type="password"
                                   v-model="form.password"
                                   :class="{'is-invalid': form.errors.password}"
                                   class="form-control" placeholder="Пароль">

                            <div v-if="form.errors.password" class="error invalid-feedback">
                                {{ form.errors.password }}
                            </div>
                        </div>
                        <div class="row">

                            <!-- /.col -->
                            <div class="col">
                                <button :disabled="form.processing" type="submit" class="btn btn-primary btn-block">Сохранить</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <!-- /.social-auth-links -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</template>

<script>
import {Head, Link, useForm} from '@inertiajs/vue3'
import AuthLayout from "../../Layouts/AuthLayout.vue";

export default {
    layout: AuthLayout,
    components: {Link, Head},
    data() {
        return {
            form: useForm({
                password: null
            }),
        }
    },
    methods: {
        submit() {
            this.form.post('/reset-password-firstly');
        }
    },
}
</script>
