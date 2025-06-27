/*eslint-disable */
let Public = {}

/**
 * 替换对象中指定的替换值
 * @param data
 * @param replace
 * @returns {*}
 */
Public.dataReplace = (data, replace) => {
    for (let value of data) {
      for (let key in value) {
        if (!Object.prototype.hasOwnProperty.call(value, key)) {
          continue
        }
  
        if (Object.prototype.hasOwnProperty.call(replace, key)) {
          value[key] = replace[key][value[key]]
        }
      }
    }
  
    return data
  }
export { Public }
