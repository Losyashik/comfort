const { defineConfig } = require("@vue/cli-service");

module.exports = defineConfig({
  transpileDependencies: true,
});
module.exports = {
  configureWebpack: {},
  devServer: {
    host: "localhost",
    hot: true,
    port: 8080,
    open: "Chrome",
    proxy: {
      //https://cli.vuejs.org/guide/html-and-static-assets.html#disable-index-generation
      "/backend/*": {
        //everything from root
        target: "http://backend/",
        secure: false,
        ws: false,
      },
      "/ws/": {
        //web sockets
        target: "ws://localhost:8080/",
        secure: false,
        ws: true,
      },
      "!/": {
        //except root, which is served by webpack's devserver, to faciliate instant updates
        target: "http://backend/",
        secure: false,
        ws: false,
      },
    },
  },
};
