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
                        <form-input
                            label="Имя"
                            required
                            v-model.trim="form.name"
                            :validation-error="errors.name"
                        />

                        <form-input
                            label="Номер телефона"
                            required
                            v-model.trim="form.phone"
                            v-maska data-maska="+############"
                            placeholder="пример: +992929927233"
                            :validation-error="errors.phone"
                        />
                    </div>

                    <div class="card-footer">
                        <form-save-button
                            :is-processing="form.processing"
                            :is-editing="doctor?.id"
                        />

                        <form-cancel-button
                            :url="route('doctors.index')"
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
import { vMaska } from "maska"
import FormInput from "../../Shared/Form/FormInput.vue";
import FormSaveButton from "../../Shared/Form/FormSaveButton.vue";
import FormCancelButton from "../../Shared/Form/FormCancelButton.vue";

export default {
    props: ['doctor', 'errors'],
    components: {FormCancelButton, FormSaveButton, FormInput, Head, Link},
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
