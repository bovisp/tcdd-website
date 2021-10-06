export default function getProp (obj, props) {
    const prop = props.shift()
    if (!obj[prop] || !props.length) {
      return obj[prop]
    }
    return getProp(obj[prop], props)
  }