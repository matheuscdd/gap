<template>
    <div class="card">
        <div class="container-img">
            <img :src="photo || require('@/assets/common/truck-placeholder.png')" alt="Foto de um caminhão"/>
        </div>
        <iPlate :plate="plate"/>
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
                    :disabled="maintenances || ((getNow() - createdAt) / (1000 * 60)) > 30"
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
import { getNow } from "@/common/utils";
import iPlate from "./iPlate.vue";

export default {
    props: [
        "id",
        "photo",
        "plate",
        "maintenances",
        "createdAt",
    ],
    components: {
        iPlate
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },
        del() {
            this.$emit("del", this.id);
        },
        getNow,
    }
};
</script>
<style scoped>
.card {
    display: grid;
    grid-auto-rows: 70px;
    grid-template-columns: 25% 45% 30%;
    border: 2px solid var(--gray-1);
    align-items: center;
    justify-items: center;
    margin-bottom: 20px;
    width: 320px;
    border-radius: 12px;
    background-color: var(--gray-5);;
}

.container-img {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 40px;

}

.container-img > img {
    background-color: #e2e2e284;
    border: 2px solid #a4a6aa;
    border-radius: 5px;
    object-fit: cover;
    width: 50px;
    height: 30px;
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

.btns button:disabled {
    background-color: var(--gray-1);
}

.btns button:disabled svg {
    fill: var(--gray-3);
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