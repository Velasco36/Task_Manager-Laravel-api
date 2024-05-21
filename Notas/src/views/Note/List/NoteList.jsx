import { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { OrderNote, SearchNote } from "../../../redux/actions/actions";
import NoteCard from "./NoteCard";
import PropTypes from "prop-types";

import NoteCreate from "../Create/NoteCreate";

export default function NoteList({ filter }) {
  const dispatch = useDispatch();
  const token = localStorage.getItem("Token");
  const formattedToken = token.replace(/["',]/g, ""); // Remove double quotes and commas from the token
  const list = useSelector((state) => state.filter);
  const search = useSelector((state) => state.search);
  const { notes } = list;
  const { results } = search;

 
  const [input, setInput] = useState("");

  useEffect(() => {
    if (formattedToken) {
      dispatch(OrderNote(formattedToken, filter));
    }
  }, [dispatch, formattedToken, filter]);

  useEffect(() => {
    if (input.trim() !== "") {
      // Verificar si input no está vacío ni contiene solo espacios en blanco
      dispatch(SearchNote(formattedToken, { search : input}));
    }
  }, [dispatch, formattedToken, input]);

  return (
    <div className=" p-8  rounded-md w-full">
      <div className="flex items-center space-x-10  pb-6 ">
        <div>
          <h2 className="text-gray-600 font-semibold">Lista de Tareas</h2>
        </div>
        <div className="flex-1 border-2 border-blue-200  bg-gray-50 w-1/2 flex items-center p-2 rounded-md focus-within:border-blue-500">
          {" "}
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="h-5 w-5 text-gray-400"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
          </svg>
          <input
            className="bg-gray-50  outline-none ml-1 block w-full"
            type="text"
            placeholder="search..."
            value={input}
            onChange={(e) => setInput(e.target.value)}
          />
        </div>
        <div>
          <NoteCreate formattedToken={formattedToken} />
        </div>
      </div>
      {/* Render notes or search results */}
      <div className="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        {input.trim() === ""
          ? notes &&
            notes.map((note) => (
              <NoteCard
                key={note.id}
                note={note}
                formattedToken={formattedToken}
              />
            ))
          : results &&
            results.map((result) => (
              <NoteCard
                key={result.id}
                note={result}
                formattedToken={formattedToken}
              />
            ))}
      </div>
    </div>
  );
}

NoteList.propTypes = {
  filter: PropTypes.string.isRequired,
};
