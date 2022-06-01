<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import { ref } from 'vue'

defineProps({
  qrcode: String
})

const form = ref({
    code: null
})

const isBusy = ref(false)

const submit = async () => {
    isBusy.value = true

    try {
        const { data } = await axios.post('/dashboard', form.value)

        if (data.success) {
            alert('Success!')
        } else {
            alert('Failed!')
        }
    } catch (error) {
        alert('Error!')
    }

    isBusy.value = false
}
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!

                        <div>
                            <form @submit.prevent="submit">
                                <label for="code">Verify 2FA Code: </label>
                                <input type="text" name="code" id="code" v-model="form.code">
                                <br>
                                <button :disabled="isBusy" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>
                            </form>
                        </div>
                        <br><br>
                        <h3>Scan QRCode Google Authenticator</h3>
                        <img :src="`data:image/png;base64,${qrcode}`" alt="" srcset="">
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
