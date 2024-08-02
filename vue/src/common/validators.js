import { z } from "zod";
import { user as cUser, client as cClient } from "./consts";

const {
    NAME,
    EMAIL,
    PASSWORD,
    CONFIRM_PASSWORD,
    ADDRESS,
    CELLPHONE, 
    CEP,
    CNPJ, 
} = {...cUser.keys, ...cClient.keys };

const base = Object.freeze({
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
    })
});

const msgs = Object.freeze({
    diff: (field) => `Os campos de ${field} não coincidem`,
    valid: (field) => `O campo ${field} deve ser válido`,
    required: (field) => `O campo ${field} é obrigatório`,
    min: (field, val) => `O campo ${field} não contém o mínimo de ${val} caracteres`,
    max: (field, val) => `O campo ${field} contém mais caracteres do que o permitido de ${val}`,
    size: (field, val) => `O campo ${field} deve ter o exato tamanho de dígitos de ${val}`,
});

const nonempty = /^[^$]/;

function validate(validator, data) {
    const schema = validator.safeParse(data);
    if (!schema.success) return schema.error.flatten().fieldErrors;
    return {};
}


function verify(schema, name, field, fields) {
    const errors = validate(schema, fields)[name];
    field.errors = errors || [];
}

export function verifyUser(name) {
    const handleFields = {};
    [NAME, EMAIL, PASSWORD, CONFIRM_PASSWORD, cUser.keys.TYPE.THIS]
        .forEach(key => handleFields[key] = this[key]?.value);
    const user = z.object({
        [EMAIL]: z
            .string()
            .trim()
            .toLowerCase()
            .regex(nonempty, msgs.required(cUser.trans.EMAIL))
            .email(msgs.valid(cUser.trans.EMAIL))
            .min(base.user[EMAIL].min, msgs.min(cUser.trans.EMAIL, base.user[EMAIL].min))
            .max(base.user[EMAIL].max, msgs.max(cUser.trans.EMAIL, base.user[EMAIL].max)),
    
        [PASSWORD]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cUser.trans.PASSWORD))
            .min(base.user[PASSWORD].min, msgs.min(cUser.trans.PASSWORD, base.user[PASSWORD].min))
            .max(base.user[PASSWORD].max, msgs.max(cUser.trans.PASSWORD, base.user[PASSWORD].max)),
        
        [NAME]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cUser.trans.NAME))
            .min(base.user[NAME].min, msgs.min(cUser.trans.NAME, base.user[NAME].min))
            .max(base.user[NAME].max, msgs.max(cUser.trans.NAME, base.user[NAME].max)),
    
        [CONFIRM_PASSWORD]: z.literal(handleFields[PASSWORD], {
            errorMap: () => ({ message: msgs.diff(cUser.trans.PASSWORD) }),
        }),
    
        [cUser.keys.TYPE.THIS]: z.enum([cUser.keys.TYPE.ADMIN, cUser.keys.TYPE.COMMON]) 
    });

    verify(user, name, this[name], handleFields);
}

export function verifyClient(name) {
    const handleFields = {};
    [NAME, ADDRESS, CEP, CELLPHONE, CNPJ]
        .forEach(key => handleFields[key] = this[key]?.value);
    const client = z.object({
        [NAME]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cClient.trans.NAME))
            .min(base.client[NAME].min, msgs.min(cClient.trans.NAME, base.client[NAME].min))
            .max(base.client[NAME].max, msgs.max(cClient.trans.NAME, base.client[NAME].max)),
      
        [ADDRESS]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cClient.trans.ADDRESS))
            .min(base.client[ADDRESS].min, msgs.min(cClient.trans.ADDRESS, base.client[ADDRESS].min))
            .max(base.client[ADDRESS].max, msgs.max(cClient.trans.ADDRESS, base.client[ADDRESS].max)),
        
        [CEP]: z
            .number({message: msgs.size(cClient.trans.CEP, base.client[CEP].size)})
            .int(msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .lte(Math.pow(10, base.client[CEP].size - 1), msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .gte(Math.pow(10, base.client[CEP].size) - 1, msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .optional(),

        [CNPJ]: z
            .number({message: msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size)})
            .int(msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .lte(Math.pow(10, base.client[CNPJ].size - 1), msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .gte(Math.pow(10, base.client[CNPJ].size) - 1, msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .optional(),

        [CELLPHONE]: z
            .number({message: msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size)})
            .int(msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .lte(Math.pow(10, base.client[CELLPHONE].size - 1), msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .gte(Math.pow(10, base.client[CELLPHONE].size) - 1, msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
    });
    verify(client, name, this[name], handleFields);
}