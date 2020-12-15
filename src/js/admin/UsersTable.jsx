import React from 'react'

export default function UsersTable({ cols, rows }) {
    const [sort, setSort] = React.useState(cols[0][1]);

    return (
        <div className="table-responsive">
            <table className="table table-hover">
                <thead>
                    <tr>
                        {cols.map((a, key) => <th key={key} onClick={() => setSort(a[1])}>{a[0]}</th>)}
                    </tr>
                </thead>
                <tbody>
                    {rows.sort((a, b) => (a[sort] > b[sort]) ? 1 : ((b[sort] > a[sort]) ? -1 : 0)).map((row, r) =>
                        <tr onClick={() => window.location.replace(row.url)} key={r}>
                            {cols.map((col, c) => <td key={c}>{col.slice(1).map(k => row[k]).join(',')}</td>)}
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    )
}