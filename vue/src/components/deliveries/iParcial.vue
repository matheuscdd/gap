<template>
    <div :style="{opacity: focus && focus !== id ? 0.9 : 1}">
        <li class="container-external" :style="{backgroundColor: color.colora}">
            <div class="container-internal">
                <iInternal icon="file-contract-solid" :value="id" />
                <iInternal icon="calendar-solid" :value="deliveryDate" />
                <iInternal icon="map-solid" :value="formatField(deliveryAddress, 20)" :title="deliveryAddress" />
                <iInternal
                    icon="dolly-solid"
                    :value="unloaded === 'client' ? 'Cliente' : 'Transportadora'"
                />
                <iInternal icon="user-solid" :value="formatField(driver, 10)" :title="driver"/>
                <iInternal icon="cart-flatbed-solid" :value="unloadingCost" />
                <div class="btns">
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
                    <button
                        @click="del"
                        :disabled="finished"
                        class="del"
                    >
                        <iSvg
                            :src="require('@/assets/icons/trash-solid.svg')"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                    <button @click="show" class="stocks">
                        <iSvg
                            :src="require(`@/assets/icons/bars-solid.svg`)"
                            width="16"
                            height="16"
                            fill="white"
                        />
                    </button>
                </div>
            </div>
        </li>
        <ul class="container-stocks" v-show="showStocks">
            <iStock
                v-for="el in stocks"
                :key="el.id"
                :name="el.name"
                :type="el.type"
                :weight="el.weight"
                :quantity="el.quantity"
                :extra="el.extra || 'Sem extra'"
                :finished="finished"
                :background="color.colora"
            />
        </ul>
    </div>
</template>
<script>
import { formatField, randomColor } from "@/common/utils";
import iInternal from "./iInternal.vue";
import iStock from "./iStock.vue";

export default {
    data: () => ({
        color: randomColor(0.2),
        showStocks: false,
    }),
    props: [
        "id",
        "deliveryDate",
        "deliveryAddress",
        "unloaded",
        "driver",
        "finished",
        "unloadingCost",
        "stocks",
        "focus"
    ],
    components: {
        iInternal,
        iStock
    },
    methods: {
        show() {
            this.showStocks = !this.showStocks;
            this.$emit("focused", this.showStocks ? this.id : null);
        },
        del() {
            this.$emit("del", this.id);
        },
        finish() {
            this.$emit("finish", this.id);
            this.showStocks = false;
            this.$emit("focused", this.showStocks ? this.id : null);
        },
        formatField,
    }
};
</script>
<style scoped>
.container-external {
    padding: 0 20px;
    height: 50px;
    border-radius: var(--radius-2);
    color: #fff;
    position: relative;
    overflow: hidden;
    border: 5px solid;
    user-select: none;
    backdrop-filter: blur(6px) brightness(43%);
    margin-bottom: 15px;
}

.container-internal {
    display: grid;
    grid-auto-rows: 50px;
    grid-template-columns: 10% 15% 25% 10% 20% 10% 5%;
    justify-content: center;
    align-items: center;
    grid-gap: 10px;
}

.btns button {
    border-radius: 5px;
    border: none;
    outline: none;
    width: 25px;
    height: 25px;
    cursor: pointer;
}

.btns button:disabled {
    background-color: var(--gray-3);
}
/*
TODO - Globalizar todos os botões no hover
*/ 
.btns button:hover:not(:disabled), .btns button:active:not(:disabled) {
    transition: 0.3s;
    filter: brightness(2);
}

.stocks {
    background-color: var(--gray-1);
}
/*
TODO - criar um componente para todos os botões em questão de cor
*/
.finish {
    background-color: var(--green-2);
}

.del {
    background-color: var(--red-1);
}

.container-stocks {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.btns {
    display: flex;
    justify-content: center;
    gap: 15px;
}

@media (max-width: 1100px) {
    .container-internal {
        grid-auto-rows: 50px;
        grid-template-columns: 100%;
    }

    .container-external {
        height: max-content;
    }
}
</style>
