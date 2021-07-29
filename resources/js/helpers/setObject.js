export default function fileExtension (obj) {
    let newObj = {}

    for (let property in obj) {
        newObj[property] = obj[property]
    }

    return newObj
}