<template>
    <Head>
        <title>{{category?.id ? 'Обновление данных категории' : 'Новая категория'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ category?.id ? 'Обновление данных категории' : 'Новая категория' }}</h1>
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

                        <div class="form-group">
                            <label>Описание</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.description}"
                                   v-model.trim="form.description">

                            <div v-if="errors.description" class="error invalid-feedback">
                                {{ errors.description }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ category?.id ? 'Сохранить' : 'Добавить' }}
                        </button>

                        <Link :href="route('categories.index')" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['category', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.category?.name,
                description: this.category?.description,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.category?.id) {
                this.form.post('/categories');
                return;
            }

            this.form.put(`/categories/${this.category.id}`)
        }
    }
}
</script>
