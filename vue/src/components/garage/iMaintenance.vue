<template>
    <div class="card">
        <div>{{ id }}</div>
        <div class="cost">
        {{ 
            Number(cost).toLocaleString("pt-br", {
                style: "currency",
                currency: "BRL",
            }) 
        }}
        </div>
        <div :title="service">{{ formattedService }}</div>
        <div>{{ new Date(date).toLocaleDateString("pt-BR") }}</div>
        <div class="km">{{ new Intl.NumberFormat('pt-Br').format(km) }} km</div>
        <iPlate
            :plate="plate"
        />
        <div class="btns">
            <button 
                @click="edit" 
                class="edit"
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
            >
                <iSvg 
                    :src="require('@/assets/icons/trash-solid.svg')"
                    width="16" 
                    height="16"
                    fill="white"
                />
            </button>
        </div>
    </div>
</template>
<script>
import iPlate from "./iPlate.vue";

export default {
    props: [
        "id",
        "plate",
        "cost",
        "service",
        "date",
        "km"
    ],
    components: {
        iPlate,
    },
    computed: {
        formattedService() {
            return this.service.slice(0, 30) + "...";
        },
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },
        del() {
            this.$emit("del", this.id);
        },
    }
};
</script>
<style scoped>
.card {
    display: grid;
    grid-auto-rows: 70px;
    grid-template-columns: 10% 10% 25% 10% 15% 20% 10%;
    padding: 0 10px;
    border: 2px solid var(--gray-1);
    margin-bottom: 20px;
    border-radius: 12px;
    text-align: center;
    justify-items: center;
    align-items: center;
    background-color: var(--gray-5);;
}

.cost {
    color: var(--red-1);
    font-weight: 600;
}

.km {
    color: var(--green-1);
    font-weight: 500;
}

.btns button {
    border: transparent;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    color: white;
}

.btns button.edit {
    background-color: var(--yellow-1);
}

.btns button.del {
    background-color: var(--red-1);
}

.btns {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btns button:hover:not(:disabled) {
    transition: 0.3s;
    filter: brightness(2);
}
</style>