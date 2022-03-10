document.querySelectorAll(".edrop-zone__input").forEach((inputElement) => {
  const dropZoneElement = inputElement.closest(".edrop-zone");

  dropZoneElement.addEventListener("click", (e) => {
      inputElement.click();
  });

  inputElement.addEventListener("change", (e) => {
      if (inputElement.files.length) {
          updateThumbnail(dropZoneElement, inputElement.files[0]);
      }
  });

  dropZoneElement.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropZoneElement.classList.add("edrop-zone--over");
  });

  ["dragleave", "dragend"].forEach((type) => {
      dropZoneElement.addEventListener(type, (e) => {
          dropZoneElement.classList.remove("edrop-zone--over");
      });
  });

  dropZoneElement.addEventListener("drop", (e) => {
      e.preventDefault();

      if (e.dataTransfer.files.length) {
          inputElement.files = e.dataTransfer.files;
          updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
      }

      dropZoneElement.classList.remove("edrop-zone--over");
  });
});

function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".edrop-zone__thumb");

if (file.type.startsWith("image/")) {
  
if (dropZoneElement.querySelector(".edrop-zone__prompt")) {
dropZoneElement.querySelector(".edrop-zone__prompt").remove();
}

if (!thumbnailElement) {
thumbnailElement = document.createElement("div");
thumbnailElement.classList.add("edrop-zone__thumb");
dropZoneElement.appendChild(thumbnailElement);
}

thumbnailElement.dataset.label = file.name;

const reader = new FileReader();

reader.readAsDataURL(file);
reader.onload = () => {
thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
      };
  } else {
      thumbnailElement.style.backgroundImage = null;
  }
}