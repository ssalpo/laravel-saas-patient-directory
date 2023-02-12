<template>
    <Head>
        <title>{{doctor?.id ? 'Обновление данных врача' : 'Новый врач'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ doctor?.id ? 'Обновление данных врача' : 'Новый врач' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Название</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.name}"
                                   v-model.trim="form.name">

                            <div v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ doctor?.id ? 'Сохранить' : 'Добавить' }}
                        </button>

                        <Link :href="route('doctors.index')" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['doctor', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.doctor?.name
            }),
        }
    },
    methods: {
        submit() {
            if (!this.doctor?.id) {
                this.form.post('/doctors');
                return;
            }

            this.form.put(`/doctors/${this.doctor.id}`)
        }
    }
}
</script>
