<template>
    <Head>
        <title>Список докторов для выплаты</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список докторов для выплаты</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Доктор</th>
                                <th title="Количество невыплаченных пациентов">Кол-во невып. пациентов</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(doctor, index) in doctors">
                                <td>{{ index + 1 }}</td>
                                <td>{{ doctor.name }}</td>
                                <td title="Количество невыплаченных пациентов">{{ doctor.not_paid_patients_count }}</td>
                                <td class="text-center" width="300">
                                    <Link :href="route('payments.store')" :data="{doctor_id: doctor.id}"
                                          preserve-scroll
                                          v-if="doctor.not_paid_patients_count > 0"
                                          method="post" as="button"
                                          type="button" class="btn btn-primary mr-2">
                                        Оплатить
                                    </Link>

                                    <Link :href="route('payments.show', doctor.id)"
                                          as="button"
                                          type="button"
                                          class="btn btn-default">
                                        История выплат
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Head, Link},
    props: ['doctors']
}
</script>
