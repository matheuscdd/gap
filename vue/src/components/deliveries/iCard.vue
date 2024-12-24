<template>
    <li :style="{borderLeftColor: statusColor}">
        <div class="card">
            <div>{{ id }}</div>
            <div class="revenue">
                {{
                    Number(revenue).toLocaleString("pt-br", {
                        style: "currency",
                        currency: "BRL",
                    })
                }}
            </div>
            <div class="client-name">{{ client_name }}</div>
            <div class="receipt-date">{{ new Date(receipt_date).toLocaleDateString("pt-BR") }}</div>
            <div class="delivery-date">{{ new Date(delivery_date).toLocaleDateString("pt-BR") }}</div>
            <div class="days-remaining">{{ daysRemaining }}</div>
            <div class="provider-city">{{ provider_city }}</div>
            <div class="delivery-address" :title="delivery_address">
                {{ formattedAddress }}
            </div>
            <div>
                <div class="btns">
                    <button
                        @click="view"
                        class="view"
                    >
                        <iSvg
                            :src="require('@/assets/icons/eye-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="edit"
                        class="edit"
                        :disabled="finished || has_partials"
                    >
                        <iSvg
                            :src="require('@/assets/icons/pencil-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="createPartial"
                        :disabled="finished"
                        class="createPartial"
                    >
                        <iSvg
                            :src="
                                require('@/assets/icons/cart-flatbed-solid.svg')
                            "
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="finish"
                        :disabled="finished"
                        class="finish"
                    >
                        <iSvg
                            :src="require('@/assets/icons/truck-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                </div>
            </div>
        </div>
        <div class="container-progress"></div>
        <div class="progress" :style="{width: `${(completed/total)*100}%`}"></div>
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
        "finished",
        "has_partials",
        "completed",
        "total",
    ],
    computed: {
        daysRemainingNum() {
            if (new Date() > new Date(this.delivery_date)) return -1;
            const res = Math.ceil(
                (new Date(this.delivery_date) - new Date()) /
                    (1000 * 60 * 60 * 24)
            );
            return res;
        },
        daysRemaining() {
            const res = this.daysRemainingNum;
            if (res === -1) return "Atrasado!";
            return res;
        },
        formattedAddress() {
            return this.delivery_address.slice(0, 10) + "...";
        },
        statusColor() {
            const res = this.daysRemainingNum;
            if (res === -1) return  "var(--red-1)";
            else if (res <= 7) return "var(--orange-1)";
            else if (res <= 14) return "var(--yellow-1)";
            return "var(--green-2)";
        }
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },
        createPartial() {
            this.$emit("createPartial", this.id);
        },
        finish() {
            this.$emit("finish", this.id);
        },
        view() {
            this.$emit("view", this.id);
        },
    },
};
</script>
<style scoped>
li {
    border-left: 20px solid;
    background-color: var(--gray-5);
    margin-bottom: 10px;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.container-progress, .progress {
    height: 5px;
}

.container-progress {
    background-color: var(--gray-4);
}

.progress {
    position: absolute;
    background-color: var(--green-4);
    bottom: 0;
    left: 0;
    border-radius: 0 5px 5px 0;
}

.revenue {
    color: var(--green-2);
    font-weight: 500;
}

.client-name {
    font-weight: 600;
}

.receipt-date {
    color: var(--green-1);
}

.delivery-date {
    color: var(--red-2);
}

.card {
    align-items: center;
    padding: 20px 0;
    grid-gap: 10px;
    display: grid;
    grid-auto-rows: 20px;
    text-align: center;
    grid-template-columns: 5% 10% 18% 8% 8% 8% 12% 15% 6%;
}

button {
    border: transparent;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    display: flex;
    align-items: center;
}

.btns {
    display: flex;
    justify-content: center;
    gap: 7px;
}

.btns button:hover, .btns button:active {
    transition: 0.3s;
    filter: brightness(1.5);
}

.btns .createPartial {
    background-color: var(--orange-1);
}

.btns .finish {
    background-color: var(--green-2);
}

.btns .view {
    background-color: var(--green-1);
}

.btns .edit {
    background-color: var(--yellow-1);
}

.btns button:disabled {
    background-color: var(--gray-3);
}


@media (max-width: 1350px) {
    .card {
        font-size: 13px;
        padding: 20px 0;
    }

    button {
        width: 17px;
        height: 17px;
    }

    .btns {
        gap: 2px;
    }
}

@media (max-width: 1100px) {
    .card {
        font-size: 18px;
        padding: 20px 0;
        grid-template-rows: 50px 20px 20px 20px 20px 20px 20px 20px 50px;
        grid-template-columns: 100%;
        align-items: center;
    }

    button {
        width: 25px;
        height: 25px;
    }

    .btns {
        gap: 20px;
    }
}
</style>
