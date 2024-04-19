import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'


export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js" , "resources/css/app.css" ],
            refresh: true,
            // template: { transformAssetUrls },
        }),
        // vue({
        //     template: {
        //         transformAssetUrls: {
        //             base: null,
        //             includeAbsolute: false,
        //         },
        //     },
        // }),
        vue({
            template: { transformAssetUrls }
          }),
        quasar({
            sassVariables: "resources/css/quasar-variables.sass",
        }),
        // splitVendorChunkPlugin(),
    ],
    // build: {
    //     rollupOptions: {
    //       output: {
    //         manualChunks: {
    //           // Define manual chunks here
    //           // For example:
    //           'vendor': ['vue','quasar'],
    //         //   'customChunk': ['src/custom-module']
    //         },
    //       },
    //     },
    //   },
});
