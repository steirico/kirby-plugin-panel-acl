import View from "./components/View.vue";

panel.plugin("steirico/kirby-plugin-panel-acl", {
  views: {
    'acl-pages': {
      component: View,
      icon: "check",
      label: "Your Pages"
    }
  }
});