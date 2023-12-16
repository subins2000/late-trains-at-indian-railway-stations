<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/x-javascript");
?>

/**
 * Usage
 * $.get("http://localhost:3000/collect.js.php", function(data) { (1, eval)(data) })
 */

var state = <?php echo file_get_contents("state.json"); ?>

var stationCode = "TCR"

function updateState(newState) {
  state = {
    ...state,
    ...newState
  }
  uploadFile('http://localhost:3000/update_state.php', JSON.stringify(state, null, 2))
}

function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

function redirect(url) {
  if (window.location.href === url) return
  window.location.href = "https://etrain.info/station/Thrisur-TCR/all"
}

function tableToArray(table) {
  var rows = []
  $(table).find("tbody tr").each(function() {
    const row = []
    $(this).find("td").each(function() {
      row.push($(this).text())
    })
    rows.push(row)
  })
  return rows
}

function uploadFile(url, stringData) {
  var formData = new FormData();
  formData.append('file', new File([new Blob([stringData])], "file.json"));

  $.ajax({
    url,
    data: formData,
    processData: false,
    contentType: false,
    type: 'POST'
  });
}

async function fetchTrainNumbers() {
  if (state.trains.length > 0) return;

  redirect("https://etrain.info/station/Thrisur-TCR/all")

  tableToArray($("table.data")).map(item => state.trains.push({
    number: item[0],
    name: item[1],
    source: item[2],
    destination: item[3],
    arrival: item[4],
    depart: item[5],
  }))
  updateState()
}

async function fetchDelays() {
  let trainNumber = ""
  index = 0
  while (index < state.trains.length) {
    trainNumber = state.trains[index].number
    if (!state.processedTrainNumbers.includes(trainNumber)) break
    index++

    if (index > state.trains.length) return
  }

  $(`a:contains(${trainNumber})`).click()
  await sleep(1000)
  $("a:contains('Running History')").click()
  await sleep(1000)
  $("a:contains('Running History')").click()
  await sleep(1000)
  $(`a.runStatStn[href='#${stationCode}']`).click()
  await sleep(1000)
  
  const delays = tableToArray($("#visualization").find("table"))
  updateState({ trainDelays: {
    ...state.trainDelays,
    [trainNumber]: delays
  }})

  const isUnavailable = $("body").text().includes("selected duration is not available.")

  if (isUnavailable) {
    state.unavailableTrainNumbers.push(trainNumber)
  }

  const canContinue = delays.length !== 0 || isUnavailable

  if (canContinue) {
    $(".modalCloseBtn").click()
    state.processedTrainNumbers.push(trainNumber)
    updateState()
  }
}

function init() {
  fetchTrainNumbers()
  fetchDelays()
}
