"use strict";

function initCalendar(unit_code) {
    // var KTAppCalendar = (function () {
        let calendar, validator, formEl, modalAdd, modalView, modalTitle;
        let addBtn, submitBtn, cancelBtn, closeBtn;
        let inputTitle, inputDescription, inputLocation, inputDate, inputTime, inputUnit;
        let modalViewName, modalViewDate, modalViewTime, modalViewDesc, modalViewLocation, modalViewUnit, modalViewStatus;
        let modalEditBtn, modalDeleteBtn;
        let M = {}; // event model
        let flatDate, flatTime;

        // === helper ===
        function setModelFromEvent(ev) {
            M = {
                id: ev.id,
                eventName: ev.title,
                eventDescription: ev.extendedProps?.description || "",
                eventLocation: ev.extendedProps?.location || "",
                date: ev.extendedProps?.date || moment(ev.startStr).format("YYYY-MM-DD"),
                time: ev.extendedProps?.time || moment(ev.startStr).format("HH:mm"),
                unit: ev.extendedProps?.unit || "",
                unit_code: ev.extendedProps?.unit_code || "",
                status: ev.extendedProps?.status || "",
            };
        }

        function openViewModal() {
            moment.locale('id');
            modalViewName.textContent = M.eventName;
            modalViewDate.textContent = moment(M.date).format("dddd, DD MMMM YYYY");
            modalViewTime.textContent = M.time || "-";
            modalViewDesc.textContent = M.eventDescription || "";
            modalViewLocation.textContent = M.eventLocation || "-";
            modalViewUnit.textContent = M.unit || "-";

            // status dengan warna
            let statusClass =
                M.status === "Selesai" ? "badge-light-success" :
                M.status === "Dijadwalkan" ? "badge-light-primary" :
                M.status === "Diajukan" ? "badge-light-warning" :
                M.status === "Dibatalkan" ? "badge-light-danger" :
                "badge-light-info";
            modalViewStatus.innerHTML = `<span class="badge ${statusClass} fs-7">${M.status || "Tidak diketahui"}</span>`;

            modalView.show();
        }

        // return {
        //     init: function () {

                // ==== VIEW MODAL ====
                const modalViewEl = document.getElementById("kt_modal_view_event");
                modalView = new bootstrap.Modal(modalViewEl);
                modalViewName = modalViewEl.querySelector('[data-kt-calendar="event_name"]');
                modalViewDate = modalViewEl.querySelector('[data-kt-calendar="event_date"]');
                modalViewTime = modalViewEl.querySelector('[data-kt-calendar="event_time"]');
                modalViewDesc = modalViewEl.querySelector('[data-kt-calendar="event_description"]');
                modalViewLocation = modalViewEl.querySelector('[data-kt-calendar="event_location"]');
                modalViewUnit = modalViewEl.querySelector('[data-kt-calendar="event_unit"]');
                modalViewStatus = modalViewEl.querySelector('[data-kt-calendar="event_status"]');
                // modalEditBtn = modalViewEl.querySelector("#kt_modal_view_event_edit");
                // modalDeleteBtn = modalViewEl.querySelector("#kt_modal_view_event_delete");

                // ==== FULLCALENDAR ====
                var calendarEl = document.getElementById("kt_calendar_app");
                var today = moment().startOf("day");

                calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,timeGridWeek,timeGridDay",
                    },
                    initialDate: today.format("YYYY-MM-DD"),
                    selectable: true,
                    // dateClick: function (info) {
                    //     const clickedDate = info.dateStr;
                    //     showAddModal(); // panggil fungsi yang kamu buat
                    //     // Tunggu sampai modal benar-benar tampil
                    //     $('#modalAddJadwal').on('shown.bs.modal', function () {
                    //         handleChangeSelect2();

                    //         // Inisialisasi flatpickr untuk tanggal
                    //         const datePicker = flatpickr("#date", {
                    //             altInput: true,
                    //             altFormat: "j F Y",   // tampil: 3 Oktober 2025
                    //             dateFormat: "Y-m-d",  // kirim ke server: 2025-10-03
                    //             locale: "id",
                    //             disableMobile: true,
                    //             defaultDate: clickedDate // ← isi otomatis tanggal yang diklik
                    //         });
                    //     });
                    // },
                    eventClick: function (clickInfo) {
                        const ev = clickInfo.event;
                        setModelFromEvent({
                            id: ev.id,
                            title: ev.title,
                            startStr: ev.startStr,
                            extendedProps: ev.extendedProps
                        });
                        openViewModal();
                    },
                    events: function (fetchInfo, successCallback, failureCallback) {
                        fetch(`/backend/dashboard/konsultasi/api/list-jadwal/${unit_code}`)
                            .then(res => res.json())
                            .then(data => {
                                const events = data.map(item => {
                                    const start = item.time ? `${item.date}T${item.time}` : item.date;
                                    const end = item.time ? moment(start).add(1, "hours").format() : null;
                                    return {
                                        id: String(item.id),
                                        title: item.title,
                                        start: start,
                                        end: end,
                                        extendedProps: {
                                            description: item.description || "",
                                            location: item.location || "",
                                            unit: item.unit || item.unit_code || "",
                                            unit_code: item.unit_code || "",
                                            status: item.status || "",
                                            date: item.date,
                                            time: item.time
                                        },
                                        className:
                                            item.status === "Selesai" ? "fc-event-success" :
                                            item.status === "Dijadwalkan" ? "fc-event-primary" :
                                            item.status === "Diajukan" ? "fc-event-warning" :
                                            item.status === "Dibatalkan" ? "fc-event-danger" :
                                            "fc-event-info"
                                    };
                                });
                                successCallback(events);
                            })
                            .catch(err => failureCallback(err));
                    }
                });
                calendar.render();

                // ==== DELETE BUTTON (WORKING) ====
                // modalDeleteBtn.addEventListener("click", function (evt) {
                //     evt.preventDefault();

                //     if (!M.id) {
                //         Swal.fire({
                //             text: "Kegiatan tidak ditemukan.",
                //             icon: "error",
                //             confirmButtonText: "OK",
                //             confirmButtonColor: '#388da8',
                //             customClass: { confirmButton: "btn btn-primary" },
                //         });
                //         return;
                //     }
                //     if(activeRole != 'admin'){
                //         if (M.unit_code != activeUnitCode) {
                //             Swal.fire({
                //                 text: "Anda tidak dapat menghapus kegiatan ini!",
                //                 icon: "error",
                //                 confirmButtonText: "OK",
                //                 confirmButtonColor: '#388da8',
                //                 customClass: { confirmButton: "btn btn-primary" },
                //             });
                //             return;
                //         }
                //     }

                //     Swal.fire({
                //         text: "Apakah Anda yakin ingin menghapus jadwal ini?",
                //         icon: "warning",
                //         showCancelButton: true,
                //         buttonsStyling: false,
                //         confirmButtonText: "Ya, hapus!",
                //         cancelButtonText: "Batal",
                //         customClass: {
                //             confirmButton: "btn btn-danger",
                //             cancelButton: "btn btn-active-light"
                //         },
                //     }).then(function (res) {
                //         if (res.isConfirmed) {
                //             // remove from calendar
                //             const ev = calendar.getEventById(M.id);
                //             if (ev) ev.remove();
                //             modalView.hide();

                //             fetch(`/backend/konsultasi-sop/konsultasi-sop-offline/jadwal/delete-calendar/${M.id}`, {
                //                 method: 'POST',
                //                 headers: {
                //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                //                     'Accept': 'application/json',
                //                 },
                //             })
                //                 .then(r => r.json())
                //                 .then(() => {
                //                     Swal.fire({
                //                         text: "Jadwal berhasil dihapus.",
                //                         icon: "success",
                //                         confirmButtonText: "OK",
                //                         buttonsStyling: false,
                //                         customClass: { confirmButton: "btn btn-primary" },
                //                     });
                //                 })
                //                 .catch(() => {
                //                     Swal.fire({
                //                         text: "Terjadi kesalahan saat menghapus jadwal.",
                //                         icon: "error",
                //                         confirmButtonText: "OK",
                //                         buttonsStyling: false,
                //                         customClass: { confirmButton: "btn btn-primary" },
                //                     });
                //                 });
                //         } else {
                //             Swal.fire({
                //                 text: "Jadwal tidak dihapus.",
                //                 icon: "info",
                //                 confirmButtonText: "OK",
                //                 buttonsStyling: false,
                //                 customClass: { confirmButton: "btn btn-primary" },
                //             });
                //         }
                //     });
                // });

                // modalEditBtn.addEventListener("click", function (evt) {
                //     evt.preventDefault();

                //     if (!M.id) {
                //         Swal.fire({
                //             text: "Kegiatan tidak ditemukan.",
                //             icon: "error",
                //             confirmButtonText: "OK",
                //             confirmButtonColor: '#388da8',
                //             customClass: { confirmButton: "btn btn-primary" },
                //         });
                //         return;
                //     }

                //     if(activeRole != 'admin'){
                //         if (M.unit_code != activeUnitCode) {
                //             Swal.fire({
                //                 text: "Anda tidak dapat mengubah kegiatan ini!",
                //                 icon: "error",
                //                 confirmButtonText: "OK",
                //                 confirmButtonColor: '#388da8',
                //                 customClass: { confirmButton: "btn btn-primary" },
                //             });
                //             return;
                //         }
                //     }

                //     Swal.fire({
                //         text: "Apakah Anda yakin ingin mengubah jadwal ini?",
                //         icon: "warning",
                //         showCancelButton: true,
                //         buttonsStyling: false,
                //         confirmButtonText: "Ya, ubah!",
                //         cancelButtonText: "Batal",
                //         customClass: {
                //             confirmButton: "btn btn-primary",
                //             cancelButton: "btn btn-active-light"
                //         },
                //     }).then(function (res) {
                //         if (res.isConfirmed) {
                //             // remove from calendar
                //             // const ev = calendar.getEventById(M.id);
                //             // if (ev) ev.remove();
                //             modalView.hide();

                //             if (M.id > 0) {
                //             let url = `/backend/konsultasi-sop/konsultasi-sop-offline/jadwal/ajax-show/${M.id}`;
                //             $('#tblempinfo tbody').empty();
                //             $.ajax({
                //                 url: url,
                //                 dataType: 'json',
                //                 success: function (response) {
                //                     $('#tblempinfo tbody').html(response.html);
                //                     $('#modalAddJadwal').modal('show');
                //                     handleChangeSelect2();
                //                 }
                //             });
                //         }

                            
                //         } else {
                //             Swal.fire({
                //                 text: "Jadwal tidak diubah.",
                //                 icon: "info",
                //                 confirmButtonText: "OK",
                //                 buttonsStyling: false,
                //                 customClass: { confirmButton: "btn btn-primary" },
                //             });
                //         }
                //     });
                // });
        //     }
        // };
    // })();
    
    return calendar;

}

// // === Initialize ===
// document.addEventListener("DOMContentLoaded", function () {
//     KTAppCalendar.init();
// });
