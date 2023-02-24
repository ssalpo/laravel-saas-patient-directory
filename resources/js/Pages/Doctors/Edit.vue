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
                            <label>Имя</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.name}"
                                   v-model.trim="form.name">

                            <div v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Номер телефона (необязательно)</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.phone}"
                                   v-maska data-maska="+992 (##) ###-##-##"
                                   v-model.trim="form.phone">

                            <div v-if="errors.phone" class="error invalid-feedback">
                                {{ errors.phone }}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ doctor?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('doctors.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import { vMaska } from "maska"

export default {
    props: ['doctor', 'errors'],
    components: {Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                name: this.doctor?.name,
                phone: this.doctor?.phone,
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
