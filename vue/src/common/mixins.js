import { user, userForm, client, clientForm, budget, budgetForm, delivery, deliveryForm, truck, truckForm } from "./consts";
import { base as limits } from "./validators";

export default {
    data: () => ({user, userForm, client, clientForm, limits, budget, budgetForm, delivery, deliveryForm, truck, truckForm })
};