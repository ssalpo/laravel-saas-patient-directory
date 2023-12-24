<template>
    <Head>
        <title>{{location?.id ? 'Обновление данных локации' : 'Новая локация'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ location?.id ? 'Обновление данных локации' : 'Новая локация' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <form-input
                            label="Область"
                            required
                            v-model.trim="form.region"
                            :validation-error="errors.region"
                        />

                        <form-input
                            label="Город/Район"
                            required
                            v-model.trim="form.area"
                            :validation-error="errors.area"
                        />
                    </div>

                    <div class="card-footer">
                        <form-save-button
                            :is-processing="form.processing"
                            :is-editing="location?.id"
                        />

                        <form-cancel-button
                            :url="route('locations.index')"
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
    props: ['location', 'errors'],
    components: {FormInput, FormCancelButton, FormSaveButton, Head, Link},
    data() {
        return {
            form: useForm({
                region: this.location?.region,
                area: this.location?.area,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.location?.id) {
                this.form.post('/locations');
                return;
            }

            this.form.put(`/locations/${this.location.id}`)
        }
    }
}
</script>
