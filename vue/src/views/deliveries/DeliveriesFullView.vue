<template>
    <h1>Visualizar Entrega</h1>
    <ul class="container-full">
        <iValue
            :value="fillField(delivery.id, `Num ${delivery.id}`)"
            icon="file-contract-solid"
            color="#3FFF82"
            font="26"
        />
        <iValue
            :value="fillField(creator)"
            label="Criador"
            icon="user-tie-solid"
            color="#FDBCF4"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.delivery_date,
                    handleDate(delivery.delivery_date).toLocaleDateString('pt-BR')
                )
            "
            label="Data de entrega"
            icon="calendar-solid"
            color="#56FFFD"
            font="20"
        />
        <iValue
            :value="fillField(delivery.delivery_address)"
            label="Endereço de entrega"
            icon="map-solid"
            color="#C3FF8F"
            font="20"
        />
        <iValue
            :value="fillField(delivery.provider_name)"
            label="Nome do fornecedor"
            icon="industry-solid"
            color="#5BD196"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.payment_status,
                    delivery.payment_status === 'paid' ? 'Pago' : 'Pendente'
                )
            "
            label="Status de pagamento"
            icon="money-bill-solid"
            color="#BEFFF9"
            font="20"
        />
        <iValue
            :value="fillField(delivery.provider_city)"
            label="Cidade do fornecedor"
            icon="city-solid"
            color="#FBB0DB"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.receipt_date,
                    handleDate(delivery.receipt_date).toLocaleDateString('pt-BR')
                )
            "
            label="Data de recebimento"
            icon="calendar-solid"
            color="#F8A982"
            font="20"
        />
        <iValue
            :value="
                fillField(delivery.invoice, delivery.invoice || 'Indefinida')
            "
            label="Nota fiscal"
            icon="qrcode-solid"
            color="#FAFF7D"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.unloaded,
                    delivery.unloaded === 'client'
                        ? 'Cliente'
                        : 'Transportadora'
                )
            "
            label="Descarga"
            icon="dolly-solid"
            color="#F1F2FF"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.payment_method,
                    delivery.payment_method === 'pix' ? 'Pix' : 'Boleto'
                )
            "
            label="Método de pagamento"
            icon="barcode-solid"
            color="#A47AE9"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.created_at,
                    handleDate(delivery.created_at).toLocaleDateString('pt-BR')
                )
            "
            label="Data de criação"
            icon="calendar-solid"
            color="#F2C7FF"
            font="20"
        />
        <iValue
            :value="
                Number(delivery.revenue).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                })
            "
            label="Faturamento"
            icon="coins-solid"
            color="#ABCAA6"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.travel_cost,
                    Number(delivery.travel_cost).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL',
                    })
                )
            "
            label="Custo da viagem"
            icon="gas-pump-solid"
            color="#F9A9AE"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.payment_date,
                    handleDate(delivery.payment_date).toLocaleDateString('pt-BR')
                )
            "
            label="Data de pagamento"
            icon="calendar-solid"
            color="#F871B3"
            font="20"
        />
        <iValue
            :value="fillField(driver)"
            label="Motorista"
            icon="user-solid"
            color="#8BBF4D"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.unloading_cost,
                    Number(delivery.unloading_cost).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL',
                    })
                )
            "
            label="Custo da descarga"
            icon="cart-flatbed-solid"
            color="#F9A9AE"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.finished,
                    delivery.finished ? 'Finalizada' : 'Aberta'
                )
            "
            label="Status"
            icon="truck-solid"
            color="#FBC9C9"
            font="20"
        />
        <iValue
            :value="fillField(client)"
            label="Cliente"
            icon="building-solid"
            color="#9EAEFE"
            font="20"
        />
        <iValue
            :value="
                fillField(
                    delivery.updated_at,
                    handleDate(delivery.updated_at).toLocaleDateString('pt-BR')
                )
            "
            label="Data de edição"
            icon="calendar-solid"
            color="#C0FFF7"
            font="20"
        />
        <iValue
            :value="fillField(editor)"
            label="Editor"
            icon="user-tie-solid"
            color="#FBF8B6"
            font="20"
        />
    </ul>
    <section>
        <h4>Estoque</h4>
        <ul class="container-stocks">
            <iStock
                v-for="el in delivery.stocks"
                :key="el.id"
                :name="el.name"
                :type="el.type"
                :weight="el.weight"
                :quantity="el.quantity"
                :extra="el.extra || 'Sem extra'"
                :finished="delivery.finished"
            />
        </ul>
    </section>
    <section v-if="$store.state.deliveryMod.partials.length">
        <h4>Parciais</h4>
        <ul class="container-partials">
            <iParcial
                v-for="el in $store.state.deliveryMod.partials"
                :key="el.id"
                :id="el.id"
                :delivery-date="handleDate(el.delivery_date).toLocaleDateString('pt-BR')"
                :delivery-address="el.delivery_address"
                :unloaded="el.unloaded"
                :driver="$store.state.driverMod.drivers.find(
                    (driver) => driver.id === el.driver 
                ).name"
                :finished="el.finished"
                :focus="focus"
                :unloading-cost="Number(el.unloading_cost).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL',
                    })"
                :stocks="el.stocks"
                @del="del"
                @focused="focused"
                @finish="finish"
            />
        </ul>
    </section>
</template>
<script>
import iStock from "@/components/deliveries/iStock.vue";
import iValue from "@/components/deliveries/iValue.vue";
import iParcial from "@/components/deliveries/iParcial.vue";
import { handleDate } from "@/common/utils";

// TODO - fazer função global para as conversões de moeda e data
export default {
    data: () => ({
        editor: {},
        creator: {},
        driver: {},
        client: {},
        delivery: {},
        partials: [],
        focus: null,
    }),
    components: {
        iValue,
        iStock,
        iParcial,
    },

    methods: {
        fillField(field, transformed) {
            if (field === null) return "Pendente";
            return field !== undefined ? transformed || field : "⠶⠚⠛";
        },
        focused(id) {
            this.focus = id;
        },
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja excluir essa entrega?");
            if (!continues) return;
            this.$store.dispatch("deliveryMod/delPartial", id);
        },
        async finish(id) {
            const continues = await this.$store.state.iChoice.open(`Tem certeza que deseja finalizar essa entrega parcial de número ${id}? Essa ação não poderá ser desfeita`);
            if (!continues) return;
            await this.$store.dispatch("deliveryMod/finishPartial", id);
        },
        handleDate,
    },

    async beforeCreate() {
        window.scrollTo(0, 0);
        const id = this.$route.params.id;
        await Promise.all([
            this.$store.dispatch("deliveryMod/storeDelivery", id),
            this.$store.dispatch("stockTypeMod/storeStockTypes"),
            this.$store.dispatch("userMod/storeUsers"),
            this.$store.dispatch("deliveryMod/storePartials", id),
            this.$store.dispatch("driverMod/storeDrivers"),
        ]);
        this.delivery = this.$store.state.deliveryMod.delivery;
        await this.$store.dispatch("clientMod/storeClient", this.delivery.client),
        this.client = this.$store.state.clientMod.client.name;
        this.driver = this.$store.state.driverMod.drivers.find(
            (el) => el.id === this.delivery.driver
        ).name;
        this.creator = this.$store.state.userMod.users.find(
            (el) => el.id === this.delivery.created_by
        ).name;
        this.editor = this.$store.state.userMod.users.find(
            (el) => el.id === this.delivery.updated_by
        ).name;
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

.container-full {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.container-stocks {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

section > h4 {
    font-size: 20px;
    margin-bottom: 20px;
    margin-top: 15px;
    text-align: center;
}
</style>
