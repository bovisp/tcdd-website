export default function (str) {
    let unSnakeCase = str.replace(/_/g, ' ')

    return `${unSnakeCase.charAt(0).toUpperCase()}${unSnakeCase.slice(1)}`
}