(function () {
  "use strict";
  const container = document.getElementById("app-messages");
  const messages = container.getElementsByClassName("app-message");

  for (let i = 0; i < messages.length; i++) {
    const closeBtn = messages[i].getElementsByClassName("app-message-close")[0];
    closeBtn.addEventListener("click", () =>
      container.removeChild(messages[i])
    );
  }
})();
