<template>
    <li>
        <div class="general-info">
            <div>
                <h3>{{ client_name }}</h3>
                <h4>{{ provider_name }}</h4>
                <h5>ENTREGA: {{ new Date(delivery_date).toLocaleDateString("pt-BR")  }}</h5>
                <h6>ATUALIZAÇÃO: {{ updatedAt.toLocaleString("pt-BR") }}</h6>
            </div>
            <div class="btns">
                <div class="id">
                    {{ id }}
                </div>
                <button 
                    class="create" 
                    style="display: none;"
                >
                    <iSvg 
                        :src="require('@/assets/icons/truck-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
                </button>
                <button 
                    class="edit" 
                    @click="this.$emit('edit', this.id)"
                >
                    <iSvg 
                        :src="require('@/assets/icons/pencil-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
                </button>
                <button 
                    class="del" 
                    @click="this.$emit('del', this.id)"
                    :disabled="((getNow() - createdAt) / (1000 * 60)) > 30"
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
        <div>
            <h4 class="currency">{{ Number(revenue).toLocaleString("pt-br", {style: "currency", currency: "BRL"}) }}</h4>
        </div>
        <div class="path">
            <legend>Trajeto</legend>
            <div class="container">
                <div>
                    <span>A</span>
                    <h6>{{ provider_city }}</h6>
                </div>
                <div>
                    <span>B</span>
                    <h6>{{ delivery_address }}</h6>
                </div>
            </div>
        </div>
    </li>
</template>
<script>
import { getNow } from "@/common/utils";

export default {
    props: [
        "id",
        "client_name",
        "provider_name",
        "updatedAt",
        "provider_city",
        "delivery_address",
        "revenue",
        "delivery_date",
        "createdAt",
    ],
    methods: {
        getNow,
    }
};
</script>
<style scoped>
li {
    padding: 30px;
    border: 1px solid var(--gray-1);
    width: 330px;
    height: 300px;
    background-color: white;
    border-radius: 12px;
}

h3 {
    font-size: 25px;
    height: 28px;
}

h4 {
    font-size: 20px;
    height: 23px;
}

h5 {
    font-size: 15px;
    height: 18px;
}

h6 {
    font-size: 12px;
    height: 15px;
}

.btns {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.btns button, .id {
    border-radius: 50%;
    width: 45px;
    height: 45px;
    border: transparent;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btns button {
    cursor: pointer;
}

.id {
    background-color: var(--gray-1);
}

.btns button:hover:not(:disabled), .btns button:active {
    transition: 0.3s;
    filter: brightness(2);
}

.btns .create {
    background-color: var(--green-1);
}

.btns .edit {
    background-color: var(--yellow-1);
}

.btns .del {
    background-color: var(--red-1);
}

.btns button:disabled {
    background-color: var(--gray-1);
}

.btns button:disabled svg {
    fill: var(--gray-3);
}

.general-info {
    width: 330px;
    display: flex;
    justify-content: space-between;
}

.general-info h3, .general-info h4, .general-info h5 {
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis; 
    max-width: 200px;
} 

.general-info h3 {
    margin-bottom: 8px;
}

.general-info h4 {
    margin-bottom: 5px;
}

.general-info h6 {
    margin-bottom: 20px;
}

.currency {
    color: var(--green-1);
    font-weight: 600;
    margin-bottom: 20px;
}

.path {
    color: var(--gray-1);
}

.path legend {
    margin-bottom: 8px;
}

.path .container {
    position: relative;
}

.path span {
    border: 1px solid var(--gray-1);
    border-radius: 100%;
    width: 25px;
    height: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.path .container div {
    display: flex;
    justify-content: center;
    align-items: center;
    width: max-content;
    gap: 5px;
}

.path .container div:first-child:after {
    content: " ";
    position: absolute;
    height: 20px;
    border: 1px solid var(--gray-1);
    border-style: dashed;
    top: 33px;
    left: 12px;
}

.path .container div:last-child {
    margin-top: 35px;
}

.path h6 {
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis; 
    max-width: 200px;
}
</style>