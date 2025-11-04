
var page = window.location.pathname.split("/").pop().split(".")[0];
var aux = window.location.pathname.split("/");
// Use absolute path from public directory
var base_url = window.location.origin + '/assets/';
var root = window.location.pathname.split("/")
if (!aux.includes("pages")) {
  page = "dashboard";
}

loadStylesheet(base_url + "css/perfect-scrollbar.css");
loadJS(base_url + "js/plugins/perfect-scrollbar.min.js", true);

if (document.querySelector("nav [navbar-trigger]")) {
  loadJS(base_url + "js/navbar-collapse.js", true);
}

if (document.querySelector("[data-target='tooltip']")) {
  loadJS(base_url + "js/tooltips.js", true);
  loadStylesheet(base_url + "css/tooltips.css");
}

if (document.querySelector("[nav-pills]")) {
  loadJS(base_url + "js/nav-pills.js", true);
}

if (document.querySelector("[dropdown-trigger]")) {
  loadJS(base_url + "js/dropdown.js", true);

}

if (document.querySelector("[fixed-plugin]")) {
  loadJS(base_url + "js/fixed-plugin.js", true);
}

if (document.querySelector("[navbar-main]")) {
  loadJS(base_url + "js/sidenav-burger.js", true);
  loadJS(base_url + "js/navbar-sticky.js", true);
}

if (document.querySelector("canvas")) {
  loadJS(base_url + "js/chart-1.js", true);
  loadJS(base_url + "js/chart-2.js", true);
}

function loadJS(FILE_URL, async) {
  let dynamicScript = document.createElement("script");

  dynamicScript.setAttribute("src", FILE_URL);
  dynamicScript.setAttribute("type", "text/javascript");
  dynamicScript.setAttribute("async", async);

  document.head.appendChild(dynamicScript);
}

function loadStylesheet(FILE_URL) {
  let dynamicStylesheet = document.createElement("link");

  dynamicStylesheet.setAttribute("href", FILE_URL);
  dynamicStylesheet.setAttribute("type", "text/css");
  dynamicStylesheet.setAttribute("rel", "stylesheet");

  document.head.appendChild(dynamicStylesheet);
}
