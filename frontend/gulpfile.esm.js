const { join, extname, basename } = require('path')
const { readdir, stat, copyFileSync } = require('fs')
const { promisify } = require('util')
const readdirP = promisify(readdir)
const statP = promisify(stat)

const config = {
  src: './dist/_assets',
  output: './assets/',
  copy: [
    'index.js',
    'style.css',
  ]
}

/**
 * Scan and retrieve all files from given directory
 * @param {String} dir
 * @param {Array} allFiles
 */
async function rreaddir(dir, allFiles = []) {
    const files = (await readdirP(dir)).map(f => join(dir, f))
    allFiles.push(...files)
    await Promise.all(
        files.map(
            async f => (await statP(f)).isDirectory() && rreaddir(f, allFiles)
        )
    )
    return allFiles
}

/**
 * Clean up given file path by removing hash.
 * e.g. 'dist/_assets/index.abcdefg.js' -> 'index.js'
 * @param {String} filepath
 */
function removeFileHash (filepath) {
  let fragments = basename(filepath).split('.')
  fragments.splice(fragments.length - 2, 1)
  return fragments.join('.')
}

/**
 * Copy and normalise compiled Vite files
 * @param {Closure} cb
 */
async function copy (cb) {
  let assets = await rreaddir(config.src)
  assets.forEach(filepath => {
      if (!extname(filepath)) {
        return
      }
      if (!(filepath.endsWith('.js') || (filepath.endsWith('.css')))) {
        return
      }
      let filename = removeFileHash(filepath)
      if (config.copy.indexOf(filename) === -1) {
        return
      }
      let destination = `${config.output}/${filename}`
      copyFileSync(filepath, destination)
  })
  cb();
}

exports.build = copy
