/*
const rows = [
    { id: 1, surebetId: 'one', percentage: '1.00%', sportname : 'Basketball',periodname : 'EH1(2)', updatedat : '10 sec', bookmaker : 'sportmarket', date : '30 Nov, 17:00', match : 'Australia - Denmark ', tournament : 'FIFA World Cup ', eventytpe : 'EH1(2)', coefficient : '2.02' },
    { id: 2, surebetId: 'one', percentage: '1.00%', sportname : 'Basketball',periodname : 'EH1(2)', updatedat : '10 sec', bookmaker : '1xbet ', date : '30 Nov, 17:00', match : 'Australia - Denmark ', tournament : 'FIFA World Cup ', eventytpe : 'AH2(-1.5)', coefficient : '2.02' },
    { id: 3, surebetId: 'two', percentage: '1.00%', sportname : 'Soccer',periodname : '', updatedat : '50 sec', bookmaker : 'Stoiximan', date : '30 Nov, 21:00', match : 'Xerez-Cadiz', tournament : 'Friendlies. Club Friendly', eventytpe : 'EH1(2)', coefficient : '2.02' },
    { id: 4, surebetId: 'two', percentage: '1.00%', sportname : 'Soccer',periodname : '', updatedat : '50 sec', bookmaker : 'Betvictor ', date : '30 Nov, 21:00', match : 'Australia - Denmark', tournament : 'Friendlies. Club Friendly', eventytpe : 'AH2(-1.5)', coefficient : '2.02' }
  ];
  const columns = [] = [
    { field: 'surebetId', headerName: 'id', width: 150 },
    { field: 'percentage', headerName: 'percentage', width: 150 },
    { field: 'sportname', headerName: 'sportname', width: 150 },
    { field: 'periodname', headerName: 'periodname', width: 150 },
    { field: 'updatedat', headerName: 'updatedat', width: 150 },
    { field: 'bookmaker', headerName: 'bookmaker', width: 150 },
    { field: 'date', headerName: 'date', width: 150 },
    { field: 'match', headerName: 'match', width: 150 },
    { field: 'tournament', headerName: 'tournament', width: 150 },
    { field: 'eventytpe', headerName: 'eventytpe', width: 150 },
    { field: 'coefficient', headerName: 'coefficient', width: 150 }
  ];*/
  
  /*
export default function SureBetGrid() {
    return (
        <div style={{ height: 300, width: '100%' }}>
            <DataGrid
            rows={rows} columns={columns}  initialState={{
                rowGrouping: {
                    model: ['surebetId'],
                }
            }} />
        </div>
    );
}
*/

import React, { useState, useRef, useEffect, useMemo, useCallback} from 'react';
import { render } from 'react-dom';
import { AgGridReact } from 'ag-grid-react'; // the AG Grid React Component

import 'ag-grid-community/styles/ag-grid.css'; // Core grid CSS, always needed
import 'ag-grid-community/styles/ag-theme-alpine.css'; // Optional theme CSS

const App = () => {

 const gridRef = useRef(); // Optional - for accessing Grid's API
 const [rowData, setRowData] = useState(); // Set rowData to Array of Objects, one Object per Row

 // Each Column Definition results in one Column.
 const [columnDefs, setColumnDefs] = useState([
   {field: 'make', filter: true},
   {field: 'model', filter: true},
   {field: 'price'}
 ]);

 // DefaultColDef sets props common to all Columns
 const defaultColDef = useMemo( ()=> ({
     sortable: true
   }));

 // Example of consuming Grid Event
 const cellClickedListener = useCallback( event => {
   console.log('cellClicked', event);
 }, []);

 // Example load data from sever
 useEffect(() => {
   fetch('https://www.ag-grid.com/example-assets/row-data.json')
   .then(result => result.json())
   .then(rowData => setRowData(rowData))
 }, []);

 // Example using Grid's API
 const buttonListener = useCallback( e => {
   gridRef.current.api.deselectAll();
 }, []);

 return (
   <div>

     {/* Example using Grid's API */}
     <button onClick={buttonListener}>Push Me</button>

     {/* On div wrapping Grid a) specify theme CSS Class Class and b) sets Grid size */}
     <div className="ag-theme-alpine" style={{width: 500, height: 500}}>

       <AgGridReact
           ref={gridRef} // Ref for accessing Grid's API

           rowData={rowData} // Row Data for Rows

           columnDefs={columnDefs} // Column Defs for Columns
           defaultColDef={defaultColDef} // Default Column Properties

           animateRows={true} // Optional - set to 'true' to have rows animate when sorted
           rowSelection='multiple' // Options - allows click selection of rows

           onCellClicked={cellClickedListener} // Optional - registering for Grid Event
           />
     </div>
   </div>
 );
};


export default function SureBetGrid() {
    return (
        <div style={{ height: 300, width: '100%' }}>
            <DataGrid columns={columns} rows={rows} />;
        </div>
    );
}

if (document.getElementById('SureBetGrid')) {
    ReactDOM.render(<SureBetGrid />, document.getElementById('SureBetGrid'));
}