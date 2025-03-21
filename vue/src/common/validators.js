import { object, z } from "zod";
import { 
    user as cUser, 
    client as cClient, 
    budget as cBudget, 
    delivery as cDelivery, 
    truck as cTruck,
    maintenance as cMaintenance,
    driver as cDriver,
    endpoints  
} from "./consts";
import store from "@/store";
import { itemgetter } from "./utils";

const {
    NAME,
    EMAIL,
    PASSWORD,
    CONFIRM_PASSWORD,
    ADDRESS,
    CELLPHONE, 
    CEP,
    CNPJ,
    COST,
    CLIENT,
    PAYMENT_DATE,
    PAYMENT_METHOD,
    PROVIDER_CITY,
    PROVIDER_NAME,
    STOCKS,
    REVENUE,
    UNLOADED,
    PAYMENT_STATUS,
    DELIVERY_ADDRESS,
    DELIVERY_DATE,
    DRIVER,
    TRAVEL_COST,
    UNLOADING_COST,
    RECEIPT_DATE,
    INVOICE,
    PLATE,
    AXIS,
    PHOTO,
    SERVICE,
    DATE,
    KM,
    TRUCK,
    CPF,
} = {...cUser.keys, ...cClient.keys, ...cBudget.keys, ...cDelivery.keys, ...cTruck.keys, ...cMaintenance.keys, ...cDriver.keys  };

const budgetLimits = Object.freeze({
    [DELIVERY_ADDRESS]: Object.freeze({
        min: 3,
        max: 256
    }),
    [PROVIDER_NAME]: Object.freeze({
        min: 3,
        max: 128
    }),
    [PROVIDER_CITY]: Object.freeze({
        min: 2,
        max: 128
    }),
    [STOCKS]: Object.freeze({
        max: 512
    })
});

export const base = Object.freeze({
    user: Object.freeze({
        [NAME]: Object.freeze({
            min: 3,
            max: 64
        }),
        [EMAIL]: Object.freeze({
            min: 8,
            max: 255
        }),
        [PASSWORD]: Object.freeze({
            min: 8,
            max: 64
        }),
    }),

    client: Object.freeze({
        [NAME]: Object.freeze({
            min: 8,
            max: 128,
        }),
        [ADDRESS]: Object.freeze({
            min: 20,
            max: 256
        }),
        [CEP]: Object.freeze({
            size: 8,
        }),
        [CELLPHONE]: Object.freeze({
            size: 11,
        }),
        [CNPJ]: Object.freeze({
            size: 14
        })
    }),

    budget: budgetLimits,
    delivery: Object.freeze({
        ...budgetLimits,
        [DRIVER]: Object.freeze({
            min: 3,
            max: 128,
        }),
        [INVOICE]: Object.freeze({
            size: 44
        })        
    }),
    truck: Object.freeze({
        [PLATE]: Object.freeze({
            size: 7
        }),
    }),
    maintenance: Object.freeze({
        [SERVICE]: Object.freeze({
            min: 5,
            max: 512,
        })
    }),
    driver: Object.freeze({
        [NAME]: Object.freeze({
            min: 3,
            max: 64,
        }),
        [CPF]: Object.freeze({
            size: 11,
        }),
    }),
});

const msgs = Object.freeze({
    diff: (field) => `Os campos de ${field} não coincidem`,
    valid: (field) => `O campo ${field} deve ser válido`,
    required: (field) => `O campo ${field} é obrigatório`,
    min: (field, val) => `O campo ${field} não contém o mínimo de ${val} caracteres`,
    max: (field, val) => `O campo ${field} contém mais caracteres do que o permitido de ${val}`,
    size: (field, val) => `O campo ${field} deve ter o exato tamanho de dígitos de ${val}`,
    twoCases: (field) => `O campo ${field} deve ter exatamente duas casas decimais`,
    positive: (field) => `O campo ${field} precisa ser maior que zero`,
    onlyNumbers: "Apenas números são permitidos",
});

const nonEmpty = /^[^$]/;
const onlyNumbers = /^[\d]+$/;
const alphanumeric = /[A-Z\d]/i;
const includes = (arr) => ((el) => arr.includes(el));

function validate(validator, data) {
    const schema = validator.safeParse(data);
    if (!schema.success) return schema.error.flatten().fieldErrors;
    return {};
}

function verify(schema, name, field, fields) {
    const errors = validate(schema, fields)[name];
    field.errors = errors || [];
    return errors;
}

export function verifyUser(name, obj) {
    const sel = obj || this;
    const curUser = store.state.userMod.user;
    const users = store.state.userMod.users
        .filter(({email}) => email !== curUser.email)
        .map(({email}) => email);

    const handleFields = {};
    [NAME, EMAIL, PASSWORD, CONFIRM_PASSWORD, cUser.keys.TYPE.THIS]
        .forEach(key => handleFields[key] = sel[key]?.value);
    const user = z.object({
        [EMAIL]: z
            .string()
            .trim()
            .toLowerCase()
            .regex(nonEmpty, msgs.required(cUser.trans.EMAIL))
            .email(msgs.valid(cUser.trans.EMAIL))
            .min(base.user[EMAIL].min, msgs.min(cUser.trans.EMAIL, base.user[EMAIL].min))
            .max(base.user[EMAIL].max, msgs.max(cUser.trans.EMAIL, base.user[EMAIL].max))
            .refine((el) => curUser.email === el || !users.includes(el), { message: "Email já em uso" }),
    
        [PASSWORD]: z
            .string()
            .trim()
            .regex(nonEmpty, msgs.required(cUser.trans.PASSWORD))
            .min(base.user[PASSWORD].min, msgs.min(cUser.trans.PASSWORD, base.user[PASSWORD].min))
            .max(base.user[PASSWORD].max, msgs.max(cUser.trans.PASSWORD, base.user[PASSWORD].max)),
        
        [NAME]: z
            .string()
            .trim()
            .regex(nonEmpty, msgs.required(cUser.trans.NAME))
            .min(base.user[NAME].min, msgs.min(cUser.trans.NAME, base.user[NAME].min))
            .max(base.user[NAME].max, msgs.max(cUser.trans.NAME, base.user[NAME].max)),
    
        [CONFIRM_PASSWORD]: z.literal(handleFields[PASSWORD], {
            errorMap: () => ({ message: msgs.diff(cUser.trans.PASSWORD) }),
        }),
    
        [cUser.keys.TYPE.THIS]: z.enum([cUser.keys.TYPE.ADMIN, cUser.keys.TYPE.COMMON]) 
    });

    return verify(user, name, sel[name], handleFields);
}

export function verifyClient(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [NAME, ADDRESS, CEP, CELLPHONE, CNPJ]
        .forEach(key => handleFields[key] = sel[key]?.value);
    const client = z.object({
        [NAME]: z
            .string()
            .trim()
            .regex(nonEmpty, msgs.required(cClient.trans.NAME))
            .min(base.client[NAME].min, msgs.min(cClient.trans.NAME, base.client[NAME].min))
            .max(base.client[NAME].max, msgs.max(cClient.trans.NAME, base.client[NAME].max)),
      
        [ADDRESS]: z
            .string()
            .trim()
            .regex(nonEmpty, msgs.required(cClient.trans.ADDRESS))
            .min(base.client[ADDRESS].min, msgs.min(cClient.trans.ADDRESS, base.client[ADDRESS].min))
            .max(base.client[ADDRESS].max, msgs.max(cClient.trans.ADDRESS, base.client[ADDRESS].max)),
        
        [CEP]: z
            .string()
            .regex(onlyNumbers, msgs.onlyNumbers)
            .length(base.client[CEP].size, msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .optional(),

        [CNPJ]: z
            .string()
            .length(base.client[CNPJ].size, msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .optional(),

        [CELLPHONE]: z
            .number({message: msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size)})
            .int(msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .gte(Math.pow(10, base.client[CELLPHONE].size - 1), msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .lte(Math.pow(10, base.client[CELLPHONE].size) - 1, msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
    });
    return verify(client, name, sel[name], handleFields);
}

const stockVal = z.object({
    type: z.enum(store.state.stockTypeMod.stockTypes.map(({id}) => id)),
    name: z
        .string()
        .regex(nonEmpty, msgs.required("nome"))
        .max(base.budget[STOCKS].max),
    weight: z
        .number({ message: msgs.required("peso") })
        .int("Precisa ser inteiro")
        .positive(msgs.positive("peso")),
    quantity: z
        .number({ message: msgs.required("quantidade")})
        .int("Precisa ser inteiro")
        .positive(msgs.positive("quantidade"))
});

export function verifyBudget(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [ 
        COST,
        CLIENT,
        PAYMENT_DATE,
        PROVIDER_CITY,
        PROVIDER_NAME,
        STOCKS,
        REVENUE,
        DELIVERY_ADDRESS,
        DELIVERY_DATE,
        UNLOADED.THIS,
        PAYMENT_STATUS.THIS,
        PAYMENT_METHOD.THIS,
    ].forEach(key => handleFields[key] = sel[key]?.value);
    
    const clients = store.state.clientMod.clients.map(({id}) => id);
    const budget = z.object({
        [DELIVERY_DATE]: z
            .string()
            .date(msgs.required(cBudget.trans.DELIVERY_DATE)),
        [DELIVERY_ADDRESS]: z
            .string()
            .regex(nonEmpty, msgs.required(cBudget.trans.DELIVERY_ADDRESS))      
            .min(base.budget[DELIVERY_ADDRESS].min, msgs.min(cBudget.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].min))
            .max(base.budget[DELIVERY_ADDRESS].max, msgs.max(cBudget.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].max)),
        [PROVIDER_NAME]: z
            .string()
            .regex(nonEmpty, msgs.required(cBudget.trans.PROVIDER_NAME))      
            .min(base.budget[PROVIDER_NAME].min, msgs.min(cBudget.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].min))
            .max(base.budget[PROVIDER_NAME].max, msgs.max(cBudget.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].max)),
        [PROVIDER_CITY]: z
            .string()
            .regex(nonEmpty, msgs.required(cBudget.trans.PROVIDER_CITY))      
            .min(base.budget[PROVIDER_CITY].min, msgs.min(cBudget.trans.PROVIDER_CITY, base.budget[PROVIDER_CITY].min))
            .max(base.budget[PROVIDER_CITY].max, msgs.max(cBudget.trans.PROVIDER_CITY, base.budget[PROVIDER_CITY].max)),
        [COST]: z
            .number({ message: msgs.required(cBudget.trans.COST) })
            .positive(msgs.positive(cBudget.trans.COST))
            .lte(handleFields[REVENUE], "O custo não pode ser maior que o faturamento"),
        [REVENUE]: z
            .number({ message: msgs.required(cBudget.trans.REVENUE) })
            .positive(msgs.positive(cBudget.trans.REVENUE))
            .gte(handleFields[COST], "O faturamento não pode ser menor que o custo"),
        [CLIENT]: z
            .number({ message: msgs.valid(cBudget.trans.CLIENT) })
            .refine(includes(clients), { message: msgs.valid(cBudget.trans.CLIENT) })

    });
    return verify(budget, name, sel[name], handleFields);
}

export function verifyStock(obj, name) {
    return validate(stockVal, obj)[name] || [];
}

export function verifyDelivery(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    const rawFields = [ 
        TRAVEL_COST,
        UNLOADING_COST,
        DRIVER,
        STOCKS,
        DELIVERY_ADDRESS,
        DELIVERY_DATE,
        UNLOADED.THIS,
    ];
    
    const isNotPartial = sel.$route.name !== endpoints.names.DELIVERY_CREATE_PARTIAL;
    if (isNotPartial) {
        rawFields.push(...[
            PAYMENT_STATUS.THIS,
            PAYMENT_METHOD.THIS,
            INVOICE,
            RECEIPT_DATE,
            CLIENT,
            PAYMENT_DATE,
            PROVIDER_CITY,
            PROVIDER_NAME,
            REVENUE,
        ]);
    }
    
    rawFields.forEach(key => handleFields[key] = sel[key]?.value);
    const clients = store.state.clientMod.clients.map(itemgetter("id"));
    const drivers = store.state.driverMod.drivers.map(itemgetter("id"));
    let schema = {
        [DELIVERY_DATE]: z
            .string()
            .date(msgs.required(cDelivery.trans.DELIVERY_DATE)),
        [DELIVERY_ADDRESS]: z
            .string()
            .regex(nonEmpty, msgs.required(cDelivery.trans.DELIVERY_ADDRESS))      
            .min(base.delivery[DELIVERY_ADDRESS].min, msgs.min(cDelivery.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].min))
            .max(base.delivery[DELIVERY_ADDRESS].max, msgs.max(cDelivery.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].max)),
        [UNLOADING_COST]: z
            .number({ message: msgs.required(cDelivery.trans.UNLOADING_COST) })
            .gte(-1),
        [DRIVER]: z
            .number({ message: msgs.valid(cDelivery.trans.DRIVER) })
            .refine(includes(drivers), { message: msgs.valid(cDelivery.trans.DRIVER) }),
    };
    if (isNotPartial) {
        schema = {
            ...schema,
            [RECEIPT_DATE]: z
                .string()
                .date(msgs.required(cDelivery.trans.RECEIPT_DATE)),
            [INVOICE]: z
                .string()
                .min(base.delivery[INVOICE].size, msgs.min(cDelivery.trans.INVOICE, base.delivery[INVOICE].size))
                .optional()
                .or(z.literal(""))
                .or(z.literal(null)),
            [PROVIDER_NAME]: z
                .string()
                .regex(nonEmpty, msgs.required(cDelivery.trans.PROVIDER_NAME))      
                .min(base.delivery[PROVIDER_NAME].min, msgs.min(cDelivery.trans.PROVIDER_NAME, base.delivery[PROVIDER_NAME].min))
                .max(base.delivery[PROVIDER_NAME].max, msgs.max(cDelivery.trans.PROVIDER_NAME, base.delivery[PROVIDER_NAME].max)),
            [PROVIDER_CITY]: z
                .string()
                .regex(nonEmpty, msgs.required(cDelivery.trans.PROVIDER_CITY))      
                .min(base.delivery[PROVIDER_CITY].min, msgs.min(cDelivery.trans.PROVIDER_CITY, base.delivery[PROVIDER_CITY].min))
                .max(base.delivery[PROVIDER_CITY].max, msgs.max(cDelivery.trans.PROVIDER_CITY, base.delivery[PROVIDER_CITY].max)),
            [TRAVEL_COST]: z
                .number({ message: msgs.required(cDelivery.trans.TRAVEL_COST) })
                .positive(msgs.positive(cDelivery.trans.TRAVEL_COST))
                .lte(handleFields[REVENUE], "O custo de viagem não pode ser maior que o faturamento"),
            [REVENUE]: z
                .number({ message: msgs.required(cDelivery.trans.REVENUE) })
                .positive(msgs.positive(cDelivery.trans.REVENUE))
                .gte(handleFields[COST], "O faturamento não pode ser menor que o custo"),
            [CLIENT]: z
                .number({ message: msgs.valid(cDelivery.trans.CLIENT) })
                .refine(includes(clients), { message: msgs.valid(cDelivery.trans.CLIENT) }),
        };
    }
    const delivery = z.object(schema);

    return verify(delivery, name, sel[name], handleFields);
}

export function verifyTruck(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [PLATE, AXIS, PHOTO]
        .forEach(key => handleFields[key] = sel[key]?.value);
    
    const truck = z.object({
        [PLATE]: z
            .string()
            .regex(nonEmpty, msgs.required(cTruck.trans.PLATE))
            .regex(alphanumeric, "Apenas números e letras são permitidos")
            .length(base.truck[PLATE].size, msgs.size(cTruck.trans.PLATE, base.truck[PLATE].size)),
        [AXIS]: z
            .number({message: msgs.required(cTruck.trans.AXIS)})
            .positive("O mínimo de eixos é 1")
            .lte(7, "O máximo de eixos é 7"),
        [PHOTO]: z
            .string()
            .optional()
    });
    return verify(truck, name, sel[name], handleFields);
}



export function verifyMaintenance(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [TRUCK, COST, DATE, KM, SERVICE]
        .forEach(key => handleFields[key] = sel[key]?.value);

    const trucks = store.state.truckMod.trucks.map(itemgetter("id"));
    const truck = z.object({
        [SERVICE]: z
            .string()
            .regex(nonEmpty, msgs.required(cMaintenance.trans.SERVICE))      
            .min(base.maintenance[SERVICE].min, msgs.min(cMaintenance.trans.SERVICE, base.maintenance[SERVICE].min))
            .max(base.maintenance[SERVICE].max, msgs.max(cMaintenance.trans.SERVICE, base.maintenance[SERVICE].max)),
        [DATE]: z
            .string()
            .date(msgs.required(cMaintenance.trans.DATE)),
        [COST]: z
            .number({ message: msgs.required(cMaintenance.trans.COST) })
            .positive(msgs.positive(cMaintenance.trans.COST)),
        [KM]: z
            .number({ message: msgs.required(cMaintenance.trans.KM) })
            .positive(msgs.positive(cMaintenance.trans.KM)),
        [TRUCK]: z
            .number({ message: msgs.valid(cMaintenance.trans.TRUCK) })
            .refine(includes(trucks), { message: msgs.valid(cMaintenance.trans.TRUCK) }),
    });
    return verify(truck, name, sel[name], handleFields);
}

export function verifyDriver(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [NAME, CPF, PHOTO]
        .forEach(key => handleFields[key] = sel[key]?.value);
    
    const driver = z.object({
        [NAME]: z
            .string()
            .regex(nonEmpty, msgs.required(cDriver.trans.NAME))      
            .min(base.driver[NAME].min, msgs.min(cDriver.trans.NAME, base.driver[NAME].min))
            .max(base.driver[NAME].max, msgs.max(cDriver.trans.NAME, base.driver[NAME].max)),
        [CPF]: z
            .string()
            .regex(nonEmpty, msgs.required(cDriver.trans.CPF))
            .regex(onlyNumbers, msgs.onlyNumbers)
            .length(base.driver[CPF].size, msgs.size(cDriver.trans.CPF, base.driver[CPF].size)),
        [PHOTO]: z
            .string()
            .optional()
    });
    return verify(driver, name, sel[name], handleFields);
}