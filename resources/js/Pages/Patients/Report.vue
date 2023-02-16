<template>
    <Head>
        <title>Результат диагностики</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Результат диагностики</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Микроскопическое описание</label>

                            <textarea
                                class="form-control"
                                :class="{'is-invalid': errors.gender}"
                                v-model="form.microscopic_description">
                            </textarea>

                            <div v-if="errors.microscopic_description" class="error invalid-feedback">
                                {{ errors.microscopic_description }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Диагноз</label>

                            <textarea class="form-control"
                                      :class="{'is-invalid': errors.gender}"
                                      v-model="form.diagnosis">
                            </textarea>

                            <div v-if="errors.diagnosis" class="error invalid-feedback">
                                {{ errors.diagnosis }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Заметка</label>

                            <textarea class="form-control"
                                      :class="{'is-invalid': errors.gender}"
                                      v-model="form.note">
                            </textarea>

                            <div v-if="errors.note" class="error invalid-feedback">
                                {{ errors.note }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ patient?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('patients.all')" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['errors', 'patient'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                microscopic_description: this.patient?.microscopic_description,
                diagnosis: this.patient?.diagnosis,
                note: this.patient?.note,
            }),
        }
    },
    methods: {
        submit() {
            this.form.put(route('patients.update.report', this.patient.id))
        }
    }
}
</script>
