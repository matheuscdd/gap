<template>
    <li :style="{borderLeftColor: statusColor}">
        <div class="card">
            <div>{{ id }}</div>
            <div class="revenue">
                {{
                    Number(revenue).toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL",
                    })
                }}
            </div>
            <div class="client-name" :title="client_name">{{ formatField(client_name, 15) }}</div>
            <div class="receipt-date">{{ handleDate(receipt_date).toLocaleDateString("pt-BR") }}</div>
            <div class="delivery-date">{{ handleDate(delivery_date).toLocaleDateString("pt-BR") }}</div>
            <div class="days-remaining">{{ daysRemaining }}</div>
            <div class="provider-city" :title="provider_city">{{ formatField(provider_city, 10) }}</div>
            <div class="delivery-address" :title="delivery_address">
                {{ formatField(delivery_address, 10) }}
            </div>
            <div>
                <div class="btns">
                    <button
                        @click="view"
                        class="view"
                        title="Detalhes"
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
                        title="Editar"
                        :disabled="received || finished || has_partials"
                    >
                        <iSvg
                            :src="require('@/assets/icons/pencil-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="del"
                        class="del"
                        title="Remover"
                        :disabled="received || finished || has_partials || ((getNow() - createdAt) / (1000 * 60)) > 30"
                    >
                        <iSvg
                            :src="require('@/assets/icons/trash-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="receive"
                        class="received"
                        title="Receber"
                        :disabled="received || finished" 
                    >
                        <iSvg
                            :src="require('@/assets/icons/house-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button
                        @click="createPartial"
                        :disabled="finished"
                        title="Criar Parcial"
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
                        :disabled="!received || finished"
                        class="finish"
                        title="Finalizar"
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
import { formatField, getNow, handleDate } from "@/common/utils";

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
        "received",
        "has_partials",
        "completed",
        "total",
        "createdAt",
    ],
    computed: {
        daysRemainingNum() {
            if (new Date() > handleDate(this.delivery_date)) return -1;
            const res = Math.ceil(
                (handleDate(this.delivery_date) - new Date()) /
                    (1000 * 60 * 60 * 24)
            );
            return res;
        },
        daysRemaining() {
            const res = this.daysRemainingNum;
            if (res === -1) return "Atrasado!";
            return res;
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
        receive() {
            this.$emit("receive", this.id);
        },
        edit() {
            this.$emit("edit", this.id);
        },
        del() {
            this.$emit("del", this.id);
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
        getNow,
        handleDate,
        formatField,
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
    grid-template-columns: 5% 10% 11% 8% 8% 8% 10% 10% 20%;
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

.btns button:hover:not(:disabled), .btns button:active:not(:disabled) {
    transition: 0.3s;
    filter: brightness(2);
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

.btns .del {
    background-color: var(--red-1);
}

.btns .received {
    background-color: var(--purple-1);
}



.btns button:disabled {
    background-color: var(--gray-1);
}

.btns button:disabled svg {
    fill: var(--gray-3);
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
