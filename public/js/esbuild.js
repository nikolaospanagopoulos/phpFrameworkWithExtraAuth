require('esbuild').build({
    entryPoints: ['./index.js'],
    outdir:"dist",
    allowOverwrite: true,
    bundle: true,
    sourcemap: true,
    sourcesContent: false,
    watch: {
      onRebuild(error, result) {
        if (error) console.error('watch build failed:', error)
        else console.log('watch build succeeded:', result)
      },
    },
  }).then(result => {
    console.log('watching...')
  })