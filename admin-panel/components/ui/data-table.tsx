"use client"

import {
  ColumnDef,
  flexRender,
  Table as TanstackTable, // Renaming to avoid conflict with local Table component
} from "@tanstack/react-table"

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

interface DataTableProps<TData, TValue> {
  table: TanstackTable<TData>; // Expect a pre-configured table instance
  columns: ColumnDef<TData, TValue>[]; // Still needed for colSpan in "No results"
}

export function DataTable<TData, TValue>({
  table,
  columns, // Keep for colSpan
}: DataTableProps<TData, TValue>) {
  // const table = useReactTable({ // This is now done in the parent component
  //   data,
  //   columns,
  //   getCoreRowModel: getCoreRowModel(),
  // })

  return (
    <div className="rounded-md border">
      <Table>
        <TableHeader>
          {table.getHeaderGroups().map((headerGroup: import('@tanstack/react-table').HeaderGroup<TData>) => (
            <TableRow key={headerGroup.id}>
              {headerGroup.headers.map((header: import('@tanstack/react-table').Header<TData, unknown>) => {
                return (
                  <TableHead key={header.id}>
                    {header.isPlaceholder
                      ? null
                      : flexRender(
                          header.column.columnDef.header,
                          header.getContext()
                        )}
                  </TableHead>
                )
              })}
            </TableRow>
          ))}
        </TableHeader>
        <TableBody>
          {table.getRowModel().rows?.length ? (
            table.getRowModel().rows.map((row: import('@tanstack/react-table').Row<TData>) => (
              <TableRow
                key={row.id}
                data-state={row.getIsSelected() && "selected"}
              >
                {row.getVisibleCells().map((cell: import('@tanstack/react-table').Cell<TData, unknown>) => (
                  <TableCell key={cell.id}>
                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                  </TableCell>
                ))}
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={columns.length} className="h-24 text-center">
                No results.
              </TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>
    </div>
  )
}