<template>
    <li>
        <div class="revenue">{{ Number(revenue).toLocaleString("pt-br", {style: "currency", currency: "BRL"}) }}</div>
        <div>{{ client_name }}</div>
        <div class="receipt-date">{{ receipt_date }}</div>
        <div class="delivery-date">{{ delivery_date }}</div>
        <div class="days-remaining">{{ daysRemaining }}</div>
        <div>{{ provider_city }}</div>
        <div>{{ delivery_address }}</div>
        <div>
            <div class="btns">
                <button @click="edit">
                    <iSvg 
                        :src="require('@/assets/icons/pencil-solid.svg')"
                        width="16" 
                        height="16"
                        fill="currentColor"
                    />
                </button>
            </div>
        </div>
    </li>
</template>
<script>
export default {
    props: [
        "id",
        "client_name",
        "provider_city",
        "delivery_address",
        "revenue",
        "delivery_date",
        "receipt_date",
    ],
    computed: {
        daysRemaining() {
            if (new Date() > new Date(this.delivery_date)) return "Atrasado!";
            const res = parseInt((new Date(this.delivery_date) - new Date()) / (1000 * 60 * 60 * 24));
            return `${res} dias`;
        }
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },
    }
};
</script>