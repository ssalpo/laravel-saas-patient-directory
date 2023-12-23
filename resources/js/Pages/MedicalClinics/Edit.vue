<template>
    <Head>
        <title>{{medicalClinic?.id ? 'Обновление данных учреждения' : 'Новое учреждение'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ medicalClinic?.id ? 'Обновление данных учреждения' : 'Новое учреждение' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <form-input
                            label="Название"
                            required
                            v-model.trim="form.name"
                            :validation-error="errors.name"
                        />
                    </div>

                    <div class="card-footer">
                        <form-save-button
                            :is-processing="form.processing"
                            :is-editing="medicalClinic?.data.id"
                        />

                        <form-cancel-button
                            :url="route('medical-clinics.index')"
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

export default {
    props: ['medicalClinic', 'errors'],
    components: {FormInput, FormCancelButton, FormSaveButton, Head, Link},
    data() {
        return {
            form: useForm({
                name: this.medicalClinic?.data.name,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.medicalClinic?.data.id) {
                this.form.post('/medical-clinics');
                return;
            }

            this.form.put(`/medical-clinics/${this.medicalClinic.data.id}`)
        }
    }
}
</script>
