import { user, userForm, client, clientForm, budget, budgetForm } from "./consts";
import { base as limits } from "./validators";

export default {
    data: () => ({user, userForm, client, clientForm, limits, budget, budgetForm })
};