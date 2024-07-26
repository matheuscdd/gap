import { z } from "zod";
import { user as cUser } from "./consts";

const {
    NAME,
    EMAIL,
    PASSWORD,
    CONFIRM_PASSWORD,
} = cUser.keys;

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

});

const msgs = Object.freeze({
    diff: (field) => `Os campos de ${field} não coincidem`,
    valid: (field) => `O campo ${field} deve ser válido`,
    required: (field) => `O campo ${field} é obrigatório`,
    min: (field, val) => `O campo ${field} não contém o mínimo de ${val} caracteres`,
    max: (field, val) => `O campo ${field} contém mais caracteres do que o permitido de ${val}`
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
