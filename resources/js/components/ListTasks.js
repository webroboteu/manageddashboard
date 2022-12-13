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
   {field: 'id', filter: true},
   {field: 'date', filter: true},
   {field: 'quantity'},
   {field: 'dataset'},
   {field: 'sites'},
 ]);

 // DefaultColDef sets props common to all Columns
 const defaultColDef = useMemo( ()=> ({
     sortable: true
   }));

 // Example of consuming Grid Event
 const cellClickedListener = useCallback( event => {
   console.log('cellClicked', event);
   
 }, []);

 const queryString = window.location.search;
 const urlParams = new URLSearchParams(queryString);
 const projectId = urlParams.get('projectId')

 // Example load data from server
 useEffect(() => {
   fetch('api/v1/tasks/' + projectId)
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
     <button onClick={buttonListener}>Clean all</button>

     {/* On div wrapping Grid a) specify theme CSS Class Class and b) sets Grid size */}
     <div className="ag-theme-alpine" style={{width: 500, height: 500}}>

       <AgGridReact
           ref={gridRef} // Ref for accessing Grid's API

           rowData={rowData} // Row Data for Rows

           columnDefs={columnDefs} // Column Defs for Columns
           defaultColDef={defaultColDef} // Default Column Properties

           animateRows={true} // Optional - set to 'true' to have rows animate when sorted
           rowSelection='single' // Options - allows click selection of rows

           onCellClicked={cellClickedListener} // Optional - registering for Grid Event
           />
     </div>
   </div>
 );
};


export default function TaskGrid() {
    return (
        <div style={{ height: 300, width: '100%' }}>
            <DataGrid columns={columns} rows={rows} />;
        </div>
    );
}

if (document.getElementById('TasksGrid')) {
    ReactDOM.render(<TaskGrid />, document.getElementById('TasksGrid'));
}